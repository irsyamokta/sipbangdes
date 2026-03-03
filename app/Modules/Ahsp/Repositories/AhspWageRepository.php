<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;
use App\Models\AhspComponentWage;

class AhspWageRepository
{
    public function getAhspWages($ahspId)
    {
        return Ahsp::findOrFail($ahspId)
            ->ahspComponentWages()
            ->with('masterWage')
            ->get();
    }

    public function find($id)
    {
        return AhspComponentWage::findOrFail($id);
    }

    public function create(array $data)
    {
        return AhspComponentWage::create($data);
    }

    public function update(AhspComponentWage $ahspWage, array $data)
    {
        return $ahspWage->update($data);
    }

    public function delete(AhspComponentWage $ahspWage)
    {
        return $ahspWage->delete();
    }
}
