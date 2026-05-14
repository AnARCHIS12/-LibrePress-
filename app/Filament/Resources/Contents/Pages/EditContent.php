<?php

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use App\Filament\Support\BlockEditorMapper;
use App\Models\Content;
use App\Services\AuditLogger;
use App\Services\BlockRenderer;
use App\Services\PublicCache;
use App\Services\SearchIndexer;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditContent extends EditRecord
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $bodyJson = is_array($data['body_json'] ?? null) ? $data['body_json'] : [];
        $data['editor_blocks'] = BlockEditorMapper::fromDocument($bodyJson);

        return $data;
    }

    protected function beforeSave(): void
    {
        $record = $this->getRecord();

        if (! $record instanceof Content) {
            return;
        }

        $record->revisions()->create([
            'user_id' => auth()->id(),
            'title' => $record->title,
            'body_json' => $record->body_json,
            'body_html' => $record->body_html,
            'meta' => $record->meta,
        ]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['body_json'] = BlockEditorMapper::toDocument($data['editor_blocks'] ?? []);
        unset($data['editor_blocks']);

        if (($data['status'] ?? null) === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);

        $this->refreshDerivedContent($record);

        return $record;
    }

    private function refreshDerivedContent(Model $record): void
    {
        if (! $record instanceof Content) {
            return;
        }

        $record->update(['body_html' => app(BlockRenderer::class)->render($record)]);
        app(SearchIndexer::class)->index($record);
        app(PublicCache::class)->forgetContent($record);
        app(AuditLogger::class)->log(request(), 'content.updated.filament', $record, ['type' => $record->type]);
    }
}
