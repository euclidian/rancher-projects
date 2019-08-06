<?php

Route::get('/tiketux/rancherprojects/api/list', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listAll');

Route::get('/tiketux/rancher/stack/api/liststack', 'Tiketux\RancherProjects\Api\RancherStackApi@listStackAll');
Route::post('/tiketux/rancher/stack/api/detailstack', 'Tiketux\RancherProjects\Api\RancherStackApi@detailStackOnline');
Route::get('/tiketux/rancher/stack/api/liststackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@listStackDB');
Route::post('/tiketux/rancher/stack/api/addstackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@addStacktoDB');
Route::post('/tiketux/rancher/stack/api/cekstackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@cekStackDB');
Route::post('/tiketux/rancher/stack/api/deletestackdb', 'Tiketux\RancherProjects\Api\RancherStackApi@deleteStackinDB');

Route::post('/tiketux/rancher/stack/api/listserviceonstack', 'Tiketux\RancherProjects\Api\RancherServiceApi@listServiceOnStack');
Route::post('/tiketux/rancher/stack/api/addservicetodb', 'Tiketux\RancherProjects\Api\RancherServiceApi@addServicetoDB');
Route::post('/tiketux/rancher/stack/api/cekserviceindb', 'Tiketux\RancherProjects\Api\RancherServiceApi@cekServiceOnDB');
Route::post('/tiketux/rancher/stack/api/detailservicestackdb', 'Tiketux\RancherProjects\Api\RancherServiceApi@detailServiceStackOnDB');
Route::post('/tiketux/rancher/stack/api/detailservicestack', 'Tiketux\RancherProjects\Api\RancherServiceApi@detailServiceOnline');
Route::post('/tiketux/rancher/stack/api/deleteservicestackdb', 'Tiketux\RancherProjects\Api\RancherServiceApi@deleteServiceStackOnDB');
Route::post('/tiketux/rancher/stack/api/updateservicetodb', 'Tiketux\RancherProjects\Api\RancherServiceApi@updateServicetoDB');
Route::post('/tiketux/rancher/stack/api/detailservice', 'Tiketux\RancherProjects\Api\RancherServiceApi@detailServiceOnDB');

Route::get('/tiketux/rancherprojects/api/template/list', 'Tiketux\RancherProjects\Api\RancherTemplateApi@listAll');
Route::get('/tiketux/rancherprojects/api/template/detail/{id}', 'Tiketux\RancherProjects\Api\RancherTemplateApi@detailTemplate');
Route::post('/tiketux/rancherprojects/api/template/save', 'Tiketux\RancherProjects\Api\RancherTemplateApi@saveTemplate');
Route::get('/tiketux/rancherprojects/api/template/delete/{id}', 'Tiketux\RancherProjects\Api\RancherTemplateApi@deleteTemplate');