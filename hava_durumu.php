
<!DOCTYPE html>
<html>
<head>
<title>Hava Durumu Class</title>
</head>
	<?php


	class hava_durumu {

		function hava_durumu($sehir) {
            $api ="xxxxxxxxxxxxxx";// sizin api kodunuz
            $this->api =$api;
			$json_string = file_get_contents("http://api.wunderground.com/api/$api/forecast10day/lang:TR/q/TR/$sehir.json");
			$json_saat_string = file_get_contents("http://api.wunderground.com/api/$api/hourly/lang:TR/q/TR/$sehir.json");
			$parsed_json = json_decode($json_string);
			$parsed_saat_json = json_decode($json_saat_string);
			$this->sehir = $sehir;
			$this->json = $parsed_json;
			$this->saat_json = $parsed_saat_json;

		}



		function bugun_karsilastir() {

			$bugun_tarih = $this->json->{'forecast'}->{'simpleforecast'}->{'forecastday'}[0]->{'date'}->{'pretty'};
			$bho_tarih = date('Ymd', strtotime('-1 weeks'));
			$json_string_honce = file_get_contents("http://api.wunderground.com/api/$this->api/history_$bho_tarih/lang:TR/q/TR/$this->sehir.json");
			$parsed_saat_hojson = json_decode($json_string_honce);
			$ho_hava_durumu = $parsed_saat_hojson->{'history'}->{'dailysummary'}[0]->{'meantempm'};
			$gun = $this->json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'title'};
			$hava_durumu =$this->json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
			$hs_hava_durumu = $this->json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[7]->{'fcttext_metric'};

			echo "Bugün günlerden ".$gun." ".$bugun_tarih." Hava durumu ".$hava_durumu." Bir hafta önce Bugün hava, en yüksek ".$ho_hava_durumu."°C idi. ve Bir hafta sonra Bugün hava ".$hs_hava_durumu."°C olacak." ;
		}


		function bugun_hava() {

			$gun = $this->json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'title'};
			$hava_durumu =$this->json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
			$this->gun = $gun;
			$this->hava_durumu = $hava_durumu;

			echo "Bugün günlerden ".$gun." hava ".$hava_durumu;
		}

		function on_gunluk_hava() {

			foreach ($this->json->{'forecast'}->{'simpleforecast'}->{'forecastday'} as $key => $value){
				echo $value->{'date'}->{'weekday'} ." ".$value->{'date'}->{'pretty'}." Hava ".$value->{'conditions'}." ".$value->{'high'}->{'celsius'}."°C <br>"  ;

			}
		}

		function saate_gore_hava() {
			foreach ($this->saat_json->{'hourly_forecast'} as $key => $value){
				echo $value->{'FCTTIME'}->{'weekday_name'}." Saat ".$value->{'FCTTIME'}->{'hour'}.":".$value->{'FCTTIME'}->{'min'}."' de hava ".$value->{'temp'}->{'metric'}."°C<br>";

			}



		}



	}
	// örnek kullanımlar

	$hava = new hava_durumu("zonguldak");// şehir değiştirilerek istenilen şehre göre hava durumu alınabilir.

	$hava -> bugun_hava();

	$hava -> saate_gore_hava();

	$hava -> on_gunluk_hava();

	$hava -> bugun_karsilastir();
	?>

<body>
</body>
</html>


