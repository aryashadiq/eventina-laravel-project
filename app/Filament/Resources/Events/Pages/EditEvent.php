<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    /**
     * Temporarily store venue data extracted from the form so we can create/update the related model
     * after the event has been updated.
     *
     * @var array|null
     */
    protected ?array $venueData = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Add venue attributes to the form state when filling the form so nested inputs like
     * "venue.name" and "venue.address" get pre-filled on the edit page.
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if ($record instanceof Model && $record->relationLoaded('venue') === false) {
            $record->load('venue');
        }

        if ($record->venue) {
            $data['venue'] = [
                'name' => $record->venue->name,
                'address' => $record->venue->address,
            ];
        }

        return $data;
    }

    /**
     * Extract venue data before saving so Event is updated with only its attributes.
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['venue']) && is_array($data['venue'])) {
            $this->venueData = $data['venue'];
            unset($data['venue']);
        }

        return $data;
    }

    /**
     * After the Event is updated, create or update the related Venue record.
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     * @param array<string,mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);

        if (!is_null($this->venueData)) {
            if ($record->venue) {
                $record->venue()->update($this->venueData);
            } else {
                $record->venue()->create($this->venueData);
            }
        }

        return $record;
    }
}
