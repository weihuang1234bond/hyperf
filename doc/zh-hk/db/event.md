# 事件
模型事件實現於 [psr/event-dispatcher](https://github.com/php-fig/event-dispatcher) 接口。

## 自定義監聽器

得益於 [hyperf/event](https://github.com/hyperf/event) 組件的支撐，用户可以很方便的對以下事件進行監聽。
例如 `QueryExecuted` , `StatementPrepared` , `TransactionBeginning` , `TransactionCommitted` , `TransactionRolledBack` 。
接下來我們就實現一個記錄 SQL 的監聽器，來説一下怎麼使用。
首先我們定義好 `DbQueryExecutedListener` ，實現 `Hyperf\Event\Contract\ListenerInterface` 接口並對類定義 `Hyperf\Event\Annotation\Listener` 註解，這樣 Hyperf 就會自動把該監聽器註冊到事件調度器中，無需任何手動配置，示例代碼如下：

```php
<?php

declare(strict_types=1);

namespace App\Listeners;

use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @Listener
 */
class DbQueryExecutedListener implements ListenerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get('sql');
    }

    public function listen(): array
    {
        return [
            QueryExecuted::class,
        ];
    }

    /**
     * @param QueryExecuted $event
     */
    public function process(object $event)
    {
        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (! Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }

            $this->logger->info(sprintf('[%s] %s', $event->time, $sql));
        }
    }
}

```

## 模型事件

模型事件與 `EloquentORM` 不太一致，`EloquentORM` 使用 `Observer` 監聽模型事件。`Hyperf` 直接使用 `鈎子函數` 來處理對應的事件。如果你還是喜歡 `Observer` 的方式，可以通過 `事件監聽`，自己實現。當然，你也可以在 [issue#2](https://github.com/hyperf/hyperf/issues/2) 下面告訴我們。

### 鈎子函數

|    事件名    |     觸發實際     | 是否阻斷 |               備註                |
|:------------:|:----------------:|:--------:|:-------------------------- --:|
|   booting    |  模型首次加載前  |    否    |    進程生命週期中只會觸發一次         |
|    booted    |  模型首次加載後  |    否    |    進程生命週期中只會觸發一次         |
|  retrieved   |    填充數據後   |    否    |  每當模型從 DB 或緩存查詢出來後觸發      |
|   creating   |    數據創建時   |    是    |                                  |
|   created    |    數據創建後   |    否    |                                  |
|   updating   |    數據更新時   |    是    |                                  |
|   updated    |    數據更新後   |    否    |                                  |
|    saving    | 數據創建或更新時 |    是    |                                  |
|    saved     | 數據創建或更新後 |    否    |                                  |
|  restoring   | 軟刪除數據回覆時 |    是    |                                  |
|   restored   | 軟刪除數據回覆後 |    否    |                                  |
|   deleting   |    數據刪除時   |    是    |                                  |
|   deleted    |    數據刪除後   |    否    |                                  |
| forceDeleted |  數據強制刪除後  |    否    |                                  |

針對某個模型的事件使用十分簡單，只需要在模型中增加對應的方法即可。例如下方保存數據時，觸發 `saving` 事件，主動覆寫 `created_at` 字段。

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\Events\Saving;

/**
 * @property $id
 * @property $name
 * @property $gender
 * @property $created_at
 * @property $updated_at
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'gender', 'created_at', 'updated_at'];

    protected $casts = ['id' => 'integer', 'gender' => 'integer'];

    public function saving(Saving $event)
    {
        $this->setCreatedAt('2019-01-01');
    }
}

```

### 事件監聽

當你需要監聽所有的模型事件時，可以很方便的自定義對應的 `Listener`，比如下方模型緩存的監聽器，當模型修改和刪除後，會刪除對應緩存。

```php
<?php

declare(strict_types=1);

namespace Hyperf\ModelCache\Listener;

use Hyperf\Database\Model\Events\Deleted;
use Hyperf\Database\Model\Events\Event;
use Hyperf\Database\Model\Events\Saved;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\ModelCache\CacheableInterface;

/**
 * @Listener
 */
class DeleteCacheListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            Deleted::class,
            Saved::class,
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof Event) {
            $model = $event->getModel();
            if ($model instanceof CacheableInterface) {
                $model->deleteCache();
            }
        }
    }
}

```