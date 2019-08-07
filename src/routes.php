<?php

Route::get('/tiketux/rancherprojects/api/list', 'Tiketux\RancherProjects\Api\RancherProjectsApi@listAll');

Route::get('/tiketux/rancher/stack/api/liststack', 'Tiketux\RancherProjects\Api\RancherStackApi@listStackAll');
Route::post('/tiketux/rancher/stack/api/addstack', 'Tiketux\RancherProjects\Api\RancherStackApi@createStack');

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

Route::get('/tiketux/rancherprojects/api/config/list', 'Tiketux\RancherProjects\Api\RancherConfigApi@listAll');
Route::get('/tiketux/rancherprojects/api/config/detail/{id}', 'Tiketux\RancherProjects\Api\RancherConfigApi@detailConfig');
Route::get('/tiketux/rancherprojects/api/config/detail_template/{id}', 'Tiketux\RancherProjects\Api\RancherConfigApi@detailTemplate');
Route::post('/tiketux/rancherprojects/api/config/save', 'Tiketux\RancherProjects\Api\RancherConfigApi@saveConfig');
Route::get('/tiketux/rancherprojects/api/config/delete/{id}', 'Tiketux\RancherProjects\Api\RancherConfigApi@deleteConfig');