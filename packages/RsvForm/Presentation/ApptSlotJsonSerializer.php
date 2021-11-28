<?php

namespace RsvForm\Presentation;

use Illuminate\Support\Collection;
use RsvForm\Domain\Models\ApptSlot\ApptSlot;

/**
 * JsonResponseのためのシリアライザ
 */
class ApptSlotJsonSerializer
{
    /**
     * @param ApptSlot $apptSlot
     * @return array
     */
    public function serialize(ApptSlot $apptSlot): array
    {
        return [
            'id' => $apptSlot->getId(),
            'courseId' => $apptSlot->getCourse()->getId(),
            'name' => $apptSlot->getName(),
            'price' => $apptSlot->getPrice(),
            'capacity' => $apptSlot->getCapacity(),
            'location' => $apptSlot->getLocation(),
            'note' => $apptSlot->getNote(),
            'reservations' => $apptSlot->getReservations(),
            'start' => $apptSlot->getStart(),
            'end' => $apptSlot->getEnd(),
            'isFull' => $apptSlot->getIsFull(),
        ];
    }

    /**
     * @param Collection|ApptSlot[] $apptSlots
     * @return array
     */
    public function serializeCollection(Collection $apptSlots): array
    {
        return array_map(
            function (ApptSlot $apptSlot) {
                return $this->serialize($apptSlot);
            },
            $apptSlots->all()
        );
    }
}
