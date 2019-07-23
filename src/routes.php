<?php

Route::get('/tiketux/rancherprojects/api/list', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listAll');
Route::get('/tiketux/rancherprojects/api/liststack', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listStackAll');
Route::get('/tiketux/rancherprojects/api/liststackdb', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listStackDB');
Route::post('/tiketux/rancherprojects/api/addstackdb', 'Tiketux\RancherProjects\Api\RancherProjectsApi@addStacktoDB');
Route::post('/tiketux/rancherprojects/api/cekstackdb', 'Tiketux\RancherProjects\Api\RancherProjectsApi@cekStackDB');
Route::post('/tiketux/rancherprojects/api/deletestackdb', 'Tiketux\RancherProjects\Api\RancherProjectsApi@deleteStackinDB');