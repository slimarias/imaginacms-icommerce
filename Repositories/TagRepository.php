<?php

namespace Modules\Icommerce\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface TagRepository extends BaseRepository
{
  public function index($page, $take, $filter, $include, $fields);
  
  public function show($filter, $include, $fields, $criteria);
}
