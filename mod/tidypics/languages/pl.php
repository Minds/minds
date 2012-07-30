<?php
/**
 * Elgg tidypics plugin Polish language pack
 * 
 */

$polish = array(
			
		// Menu items and titles
			'image' => "Obrazek",
			'images' => "Obrazki",
			'caption' => "Opis",
			'photos' => "Zdjęcia",
			'images:upload' => "Dodaj obrazki",
			'album' => "Album zdjęciowy",
			'albums' => "Albumy zdjęciowe",
			'album:yours' => "Twoje albumy",
			'album:yours:friends' => "Albumy twoich znajomych",
			'album:user' => "Albumy użytkownika %s",
			'album:friends' => "Albumy przyjaciół użytkownika %s",
			'album:all' => "Wszystkie publiczne albumy",
			'album:group' => "Albumy rejsu",
			'item:object:image' => "Zdjęcia",
			'item:object:album' => "Albumy",
			'tidypics:settings:maxfilesize' => "Maximum file size in kilo bytes (KB):",
            'tidypics:editprops' => 'Edycja obrazu Właściwości',
	
		//actions
			'album:create' => "Nowy album",
			'album:add' => "Dodaj album zdjęciowy",
			'album:addpix' => "Dodaj zdjęcia",
			'album:edit' => "Edytuj album",
			'album:delete' => "Skasuj album",
			'image:edit' => "Edytuj obrazek",
			'image:delete' => "Skasuj obrazek",
			'image:download' => "Pobierz obrazek",
		
		//forms
			'album:title' => "Tytuł albumu",
			'album:desc' => "Opis (widoczny tylko dla twórcy)",
			'album:tags' => "Tagi",
			'album:cover' => "Ustaw jako okładkę albumu?",
			'album:cover:yes' => "Tak",
			'image:access:note' => "(prawa dostępu pobierane są z ustawień albumu)",
			
		//views
			'image:total' => "Obrazki w albumie:",
			'image:by' => "Obrazek dodany przez",
			'album:by' => "Album stworzony przez",
			'album:created:on' => "Stworzono",
			'image:none' => "Nie dodano jeszcze żadnych obrazków.",
			'image:back' => "Poprzednia",
			'image:next' => "Kolejna",
		
		//widgets
			'album:widget' => "Albumy zdjęciowe",
			'album:more' => "Pokaż wszystkie albumy",
			'album:widget:description' => "Pokazuje twoje ostatnie albumy zdjęciowe",
			'album:display:number' => "Liczba wyświetlanych albumów",
			'album:num_albums' => "Liczba wyświetlanych albumów",
			
		//  river
			'image:river:created' => "wgrano %s %s %s",
			'image:river:item' => "obrazek",
			'image:river:annotate' => "%s skomentował",
			'album:river:created' => "Stworzono %s",
			'album:river:item' => "album",
			'album:river:annotate' => "%s skomentował",
				
		//  Status messages
			'image:saved' => "Twój obrazek został pomyślnie zapisany.",
			'images:saved' => "Wszystkie obrazki zostały pomyślnie zapisane.",
			'image:deleted' => "Twój obrazek został pomyślnie skasowany.",
			'image:delete:confirm' => "Czy jesteś pewien że chcesz skasować ten obrazek?",
			'images:edited' => "Twoje obrazki zostały pomyślnie zapisane.",
			'album:edited' => "Twój album został pomyślnie zapisany.",
			'album:saved' => "Twój album został pomyślnie zapisany.",
			'album:deleted' => "Twój album został skasowany.",
			'album:delete:confirm' => "Na pewno chcesz skasować ten album?",
			'album:created' => "Stworzono nowy album.",
			'tidypics:status:processing' => "Please wait while we process your picture....",
				
		//Error messages
			'image:none' => "Jeszcze nie dodano żadnych obrazków.",
			'image:uploadfailed' => "Pliki nie zapisane:",
			'image:deletefailed' => "Nie udało się skasować obrazka.",
			'image:downloadfailed' => "Nie udało się ściągnąć albumu.",
			'image:notimage' => "Akceptowane formaty to tylko: jpeg, gif i png, and the allowed file size.",
			'images:notedited' => "Nie wszystkie obrazki zostały zapisane",
			'album:none' => "Jeszcze nie dodano żadnych albumów.",
			'album:uploadfailed' => "Nie udało się zapisać twojego albumu.",
			'album:deletefailed' => "Nie udało się usunąć twojego albumu.",
			'album:blank' => "Please give this albumu a tytuł and opis."
);
					
add_translation("pl", $polish);