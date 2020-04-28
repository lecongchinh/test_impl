<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class AuthService {




    public function test($request) {

        //a đù
        // cấy mô anh cụng viết lại curl à
//        test nên viết ra lung tun rồi custom lại sau :D
        //vãi nhỉ
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.github.com/repos/giangvdm15/aht-arangi-theme/forks",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_USERPWD => "lecongchinh:123chinhlatoi97",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "User-Agent:lecongchinh",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function handleProviderCallback($request) {
        $url = "https://github.com/login/oauth/access_token";
        $data = [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'code' => $request->code
        ];
        $response = Http::withHeaders(['Accept' => 'application/json'])->post($url, $data);
        return $response->body();
    }
}
