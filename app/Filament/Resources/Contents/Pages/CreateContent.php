<?php

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use App\Filament\Support\BlockEditorMapper;
use App\Models\Content;
use App\Services\AuditLogger;
use App\Services\BlockRenderer;
use App\Services\PublicCache;
use App\Services\SearchIndexer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['body_json'] = BlockEditorMapper::toDocument($data['editor_blocks'] ?? []);
        unset($data['editor_blocks']);

        if (($data['status'] ?? null) === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);

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
        app(AuditLogger::class)->log(request(), 'content.created.filament', $record, ['type' => $record->type]);
    }
}
