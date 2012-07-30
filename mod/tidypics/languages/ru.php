<?php

/**
 * Russian language pack
 */

$russian = array(
			
		// Menu items and titles			 
			'image' => "Фотография",
			'images' => "Фотографии",
			'caption' => "Описание",		
			'photos' => "Фотки",
			'images:upload' => "Загрузить Фотки",
			'album' => "Фотоальбом",
			'albums' => "Фотоальбомы",
			'album:yours' => "Ваш Альбом",
			'album:yours:friends' => "Альбомы Ваших Друзей",
			'album:user' => "%s - Фотоальбом",
			'album:friends' => "Альбомы друзей пользователя %s",
			'album:all' => "Все Альбомы Сайта",
			'album:group' => "Альбомы Группы",
			'item:object:image' => "Фотки",
			'item:object:album' => "Альбомы",
			'tidypics:settings:maxfilesize' => "Максимальный размер файла (KB):",
			'tidypics:enablephotos' => 'Включить Альбомы Группы',
			'tidypics:editprops' => 'Редактировать данные фотогографии',
	
		//actions
			'album:create' => "Создать новый Альбом",
			'album:add' => "Добавить Альбом",
			'album:addpix' => "Добавить Фотки в Альбом",
			'album:edit' => "Редактировать Альбом",
			'album:delete' => "Стереть Альбом",
			'image:edit' => "Редактировать Фотку",
			'image:delete' => "Стереть Фотку",
			'image:download' => "Скачать Фотку",
		
		//forms
			'album:title' => "Заголовок",
			'album:desc' => "Описание",
			'album:tags' => "Тэги",
			'album:cover' => "Сдеалем Обложку Альбома?",
			'album:cover:yes' => "Да",
			'image:access:note' => "(ыровень доступа как и у основного Альбома)",
			
		//views
			'image:total' => "Всего Фоток:",
			'image:by' => "Фотка добавлена:",
			'album:by' => "Альбом принадлежит:",
			'album:created:on' => "Создан:",
			'image:none' => "Пока Фоток не добавлено",
			'image:back' => "Назад",
			'image:next' => "Вперед",
		
		//widgets
			'album:widget' => "Фотоальбомы",
			'album:more' => "Посмотреть все Альбомы",
			'album:widget:description' => "Продемонстрировать свой новый Альбом",
			'album:display:number' => "Число Альбомов для показа",
			'album:num_albums' => "Число Альбомов для показа",
			
		//  river
			'image:river:created' => "%s загрузил",
			'image:river:item' => "фотку",
			'image:river:annotate' => "комментариы к Фотке",
			'album:river:created' => "%s создал новый Альбом: ",
			'album:river:item' => "Альбом",
			'album:river:annotate' => "Комментарий к Альбому",
			
		// notifications
			'tidypics:newalbum' => 'Новый Фото Альбом',
			
				
		//  Status messages
			'image:saved' => "Ваша фотография загружена успешно.",
			'images:saved' => "Все Фотки успешно сохранены.",
			'image:deleted' => "Ваша Фотография стерта.",			
			'image:delete:confirm' => "Вы уверены, что хотите стереть эту Фотку?",
			'images:edited' => "Ваши Фотки успешно обновленны.",
			'album:edited' => "Ваш Альбом обновлен.",
			'album:saved' => "Ваш Альбом сохранен.",
			'album:deleted' => "Ваш Альбом стерт.",	
			'album:delete:confirm' => "Вы уверены, что хотите стереть этот Альбом?",
			'album:created' => "Ваш новый Альбом готов.",
			'tidypics:status:processing' => "Подозчдите пока мы загружаем фотку....",
				
		//Error messages
			'image:none' => "На данный момент никаких фоток не обнаружено.",
			'image:uploadfailed' => "Файлы не загружены:",
			'image:deletefailed' => "В данный момент эту Фотку стереть невозможно.",
			'image:downloadfailed' => "На данный момент эту фотку просмотреть невозможно.",
			'image:notimage' => "Допускаются только фотки формата jpeg, gif, или png разрешенного размера.",
			'images:notedited' => "Не все фотографии были обновленны",
			'album:none' => "Пока никаких Альбомов не имеется.",
			'album:uploadfailed' => "Ваш Альбом сохранить не удалось.",
			'album:deletefailed' => "В данный момент ваш Альбом стереть невозможно.",
			'album:blank' => "Пожалуйста создайте заголовок и описание вашего Альбома.",
);
					
add_translation("ru", $russian);
