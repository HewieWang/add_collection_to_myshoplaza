package main

import (
  "fmt"
  "strings"
  "net/http"
  "io/ioutil"
  "os"
)


var cookie=getcookie()
func getcookie()  string{
  file, err := os.Open("shoplaza.cookie")
   if err != nil {
      panic(err)
   }
   defer file.Close()
   content, err := ioutil.ReadAll(file)
   return string(content)
}

func main() {

  domain:=os.Args[1]
  title:=os.Args[2]
  img:=os.Args[3]
  file:=img[strings.LastIndex(img, "/")+1:]
  url := "https://"+domain+"/admin/api/admin/collections"
  method := "POST"

  payload := strings.NewReader(`{"id":"","number":"","title":"`+title+`","description":"","product_count":0,"seo_title":"`+title+`","seo_description":"","seo_keywords":[],"independent_seo":false,"handle":"`+title+`","rule_modules":[],"disjunctive":false,"sort_order":{"by":"sales","direction":"desc"},"smart":false,"seo_url":"","updated_at":"","image":{"url":"`+img+`","folder":"url_upload","filename":"","size":0,"type":"","origin_url":"","desc":"","aspect_ratio":"0.8640000","width":540,"height":625,"store_id":290134,"created_at":"2022-01-17T08:42:29+08:00","updated_at":"2022-01-17T08:42:29+08:00","path":"`+file+`","isChecked":true,"src":"`+img+`"},"products":[],"newly_product_placed_first":false,"_":1642406922690}`)

  client := &http.Client {
  }
  req, err := http.NewRequest(method, url, payload)

  if err != nil {
    fmt.Println(err)
    return
  }
  req.Header.Add("authority", domain)
  req.Header.Add("pragma", "no-cache")
  req.Header.Add("cache-control", "no-cache")
  req.Header.Add("sec-ch-ua", "\" Not A;Brand\";v=\"99\", \"Chromium\";v=\"96\", \"Google Chrome\";v=\"96\"")
  req.Header.Add("accept", "application/json, text/plain, */*")
  req.Header.Add("content-type", "application/json")
  req.Header.Add("sec-ch-ua-mobile", "?0")
  req.Header.Add("user-agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36")
  req.Header.Add("sec-ch-ua-platform", "\"Windows\"")
  req.Header.Add("origin", "https://"+domain)
  req.Header.Add("sec-fetch-site", "same-origin")
  req.Header.Add("sec-fetch-mode", "cors")
  req.Header.Add("sec-fetch-dest", "empty")
  req.Header.Add("referer", "https://"+domain+"/admin/smart_apps/base/collections/_new")
  req.Header.Add("accept-language", "zh-CN,zh;q=0.9")
  req.Header.Add("cookie", cookie)

  res, err := client.Do(req)
  if err != nil {
    fmt.Println(err)
    return
  }
  defer res.Body.Close()

  body, err := ioutil.ReadAll(res.Body)
  if err != nil {
    fmt.Println(err)
    return
  }
  fmt.Println(string(body))
}
