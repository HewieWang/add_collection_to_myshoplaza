<?php

$domain="xxx.myshoplaza.com";

$arr=json_decode(file_get_contents("/www/shoplaza/collections.json"), true)["collections"];

foreach ($arr as $v){
    add_collection($domain,$v['title'],$v['description'],$v['image']['src']);
}
function shell_get_url($domain,$url){
    return exec("./upload ".$domain." ".$url);
}
function add_collection($domain,$title,$desc,$images){
    $url="https://".$domain."/admin/api/admin/collections";
    if(strstr($images,"staticdj")){
        $img=$images;
        $file=substr($img,strrpos($img,'/',-1)+1);
    }else{
        if($images!=""&&$images!=null){
            // $img=upload($domain,$images);
            $img=shell_get_url($domain,$images);
            $file=substr($img,strrpos($img,'/',-1)+1);
        }else{
            $img="";$file="";
        }
    }
    $post_data='{"id":"","number":"","title":"'.$title.'","description":"'.$desc.'","product_count":0,"seo_title":"'.$title.'","seo_description":"'.$desc.'","seo_keywords":[],"independent_seo":false,"handle":"111","rule_modules":[],"disjunctive":false,"sort_order":{"by":"sales","direction":"desc"},"smart":false,"seo_url":"","updated_at":"","image":{"url":"'.$img.'","folder":"url_upload","filename":"","size":0,"type":"","origin_url":"","desc":"","aspect_ratio":"0.8640000","width":540,"height":625,"store_id":290134,"created_at":"2022-01-17T08:42:29+08:00","updated_at":"2022-01-17T08:42:29+08:00","path":"'.$file.'","isChecked":true,"src":"'.$img.'"},"products":[],"newly_product_placed_first":false,"_":1642406922690}';
    fuck_post($domain,$url,$post_data);
    echo $title."添加成功".PHP_EOL;
}
function upload($domain,$image){
    $url="https://".$domain."/admin/api/images/upload";
    $post_data='{"urls":["'.$image.'"]}';
    $json=fuck_post($domain,$url,$post_data);
    $arr=json_decode($json, true);
    $task_id=$arr['task_id'];
    get_url($task_id);
}
function get_url($task_id){
    $res=fuck_get($domain,"https://".$domain."/admin/api/image-upload/schedule");
    var_dump($res);
    $arr=json_decode($res, true);
    if($arr['finished']==1&&$arr['status']==2&&$arr['task_id']==$task_id){
        return $arr['success'][0];
    }else{
        get_url($task_id);
    }
    
}
function fuck_post($domain,$url,$post_data){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => FALSE,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$post_data,
      CURLOPT_HTTPHEADER => array(
        'authority: '.$domain,
        'pragma: no-cache',
        'cache-control: no-cache',
        'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="96", "Google Chrome";v="96"',
        'accept: application/json, text/plain, */*',
        'content-type: application/json',
        'sec-ch-ua-mobile: ?0',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36',
        'sec-ch-ua-platform: "Windows"',
        'origin: https://'.$domain,
        'sec-fetch-site: same-origin',
        'sec-fetch-mode: cors',
        'sec-fetch-dest: empty',
        'referer: https://'.$domain.'/admin/smart_apps/base/collections/_new',
        'accept-language: zh-CN,zh;q=0.9',
        'cookie: '.file_get_contents("shoplaza.cookie")
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    return $response;
}

function fuck_get($domain,$url){
    try {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_SSL_VERIFYHOST => FALSE,
          CURLOPT_SSL_VERIFYPEER => FALSE,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'authority: '.$domain,
            'pragma: no-cache',
            'cache-control: no-cache',
            'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="96", "Google Chrome";v="96"',
            'accept: application/json, text/plain, */*',
            'sec-ch-ua-mobile: ?0',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-site: same-origin',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'referer: https://'.$domain.'/admin/smart_apps/base/collections/_new',
            'accept-language: zh-CN,zh;q=0.9',
            'cookie: '.file_get_contents("shoplaza.cookie")
          ),
        ));
        $response = curl_exec($curl);
        
        
        curl_close($curl);
        return $response;
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
    
}

