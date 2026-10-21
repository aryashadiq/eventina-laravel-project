<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    /**
     * Temporarily store venue data extracted from the form so we can create the related model
     * after the event record has been created.
     *
     * @var array|null
     */
    protected ?array $venueData = null;

    /**
     * Extract venue data before creating the Event record so it doesn't attempt to mass-assign
     * unknown attributes on the Event model.
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['venue']) && is_array($data['venue'])) {
            $this->venueData = $data['venue'];
            unset($data['venue']);
        }

        return $data;
    }

    /**
     * After the event record is created, create the related Venue if venue data was provided.
     *
     * @param array<string,mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);

        if (!empty($this->venueData)) {
            // Use the relationship to create the venue with the correct event_id
            $record->venue()->create($this->venueData);
        }

        return $record;
    }
}
