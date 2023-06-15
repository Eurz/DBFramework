<?php

namespace App\Entities;

use Core\Entity;

class MissionsEntity extends Entity
{

    private int $id;
    private string $title;
    private string $description;
    private string $codeName;
    private int $countryId;
    private array $agents;
    private array $contacts;
    private array $targets;
    private int $missionTypeId;
    private int $status;
    private array $hidings;
    private int $requiredSpecialityId;
    private string $startDate;
    private string $endDate;
}
