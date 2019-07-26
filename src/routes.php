<?php

Route::get('/tiketux/rancherprojects/api/list', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listAll');
Route::get('/tiketux/rancher/stack/api/liststack', 'Tiketux\RancherProjects\Api\RancherStackApi@listStackAll');
Route::post('/tiketux/rancher/stack/api/detailstack', 'Tiketux\RancherProjects\Api\RancherStackApi@detailStackOnline');
Route::get('/tiketux/rancher/stack/api/liststackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@listStackDB');
Route::post('/tiketux/rancher/stack/api/addstackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@addStacktoDB');
Route::post('/tiketux/rancher/stack/api/cekstackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@cekStackDB');
Route::post('/tiketux/rancher/stack/api/deletestackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@deleteStackinDB');
Route::post('/tiketux/rancher/stack/api/listserviceonstack', 'Tiketux\RancherProjects\Api\RancherStackApi@listServiceOnStack');
Route::post('/tiketux/rancher/stack/api/addservicetodb', 'Tiketux\RancherProjects\Api\RancherStackApi@addServicetoDB');
Route::post('/tiketux/rancher/stack/api/cekserviceindb', 'Tiketux\RancherProjects\Api\RancherStackApi@cekServiceOnDB');
Route::post('/tiketux/rancher/stack/api/detailservicestackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@detailServiceStackOnDB');