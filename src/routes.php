<?php

Route::get('/tiketux/rancherprojects/api/list', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listAll');
Route::get('/tiketux/rancherprojects/api/liststack', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listStackAll');