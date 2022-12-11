<?php

namespace App\Repositories\Contracts;

interface AssignmentRepositoryInterface extends RepositoryInterface
{
    public function ownAssignments(array $columns = ['*'], array $relations = []);
}
