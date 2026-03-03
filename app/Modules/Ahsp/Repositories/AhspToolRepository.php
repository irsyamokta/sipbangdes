<?php

namespace App\Modules\Ahsp\Repositories;

use App\Models\Ahsp;
use App\Models\AhspComponentTool;

class AhspToolRepository
{
    public function getAhspTools($ahspId)
    {
        return Ahsp::findOrFail($ahspId)
            ->ahspComponentTools()
            ->with('masterTool')
            ->get();
    }

    public function find($id)
    {
        return AhspComponentTool::findOrFail($id);
    }

    public function create(array $data)
    {
        return AhspComponentTool::create($data);
    }

    public function update(AhspComponentTool $ahspTool, array $data)
    {
        return $ahspTool->update($data);
    }

    public function delete(AhspComponentTool $ahspTool)
    {
        return $ahspTool->delete();
    }
}
