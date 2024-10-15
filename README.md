## 資料庫測驗 - 1
    SELECT
	    bnbs.id AS bnb_id,
	    bnbs.name AS bnb_name,
	    SUM(orders.amount) AS may_amount
    FROM
	    bnbs
	    INNER JOIN orders ON orders.bnb_id = bnbs.id
    WHERE
	    orders.currency = "TWD"
	    AND orders.created_at >= "2023-05-01"
	    AND orders.created_at < "2023-06-01"
    GROUP BY bnbs.id
    ORDER BY may_amount DESC
    LIMIT 10;

## 資料庫測驗 - 2
 - 首先我會先將*GROUP BY*與*JOIN*操作與條件篩選所使用的*bnbs.id*, *orders.bnb_id*, *orders.currency*列做BTree索引。
 - 若效能仍然不夠理想，且確定日後會經常用到該類查詢（以訂單日期範圍查詢），則將orders.created_at內容以date格式另存一列，並做BTree索引，往後需以日期範圍查詢時，則使用此列。

## API 實作測驗
### 鳴謝
Docker Compose環境建置參考了*aschmelyun/docker-compose-laravel*專案，並視需求做了修改，在此感謝。

### 部署流程
 1. clone此專案
 2. 視需求修改Laravel環境變數，Laravel根目錄為`src/`
 3. 使用指令 `docker compose up -d` 以建置docker container並開始執行
 4. 使用指令 `docker compose run --rm composer update` 以安裝/更新套件
 5. 沒有意外的話，它應該能動起來了

### 單元測試

 - 使用指令 `docker exec -t *Your PHP Container Name* php artisan test` 以執行單元測試
 - 單元測試檔案位於`src/tests/Unit/` 下

###  SOLID實踐

 - 單一職責：*OrderController*僅負責接收與回應請求，輸入欄位的基本檢驗由*OrderRequest*實作，各欄位內容是否符合規定與內容轉換則由*OrderService*處理，然而convert()同時實作了內容驗證與幣值轉換，因此在此原則的貫徹上並不完整。

 - 本專案後續可透過以下方法使其更臻完善。
1. 將*OrderService*原有的convert()拆分為針對各欄位的檢查與轉換
2. 將convert()下各個例外狀況的error message定義為Constant
