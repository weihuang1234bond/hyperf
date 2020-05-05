# 模型關聯

## 定義關聯

關聯在 `Hyperf` 模型類中以方法的形式呈現。如同 `Hyperf` 模型本身，關聯也可以作為強大的 `查詢語句構造器` 使用，提供了強大的鏈式呼叫和查詢功能。例如，我們可以在 role 關聯的鏈式呼叫中附加一個約束條件：

```php
$user->role()->where('level', 1)->get();
```

### 一對一

一對一是最基本的關聯關係。例如，一個 `User` 模型可能關聯一個 `Role` 模型。為了定義這個關聯，我們要在 `User` 模型中寫一個 `role` 方法。在 `role` 方法內部呼叫 `hasOne` 方法並返回其結果:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\DbConnection\Model\Model;

class User extends Model
{
    public function role()
    {
        return $this->hasOne(Role::class, 'user_id', 'id');
    }
}
```

`hasOne` 方法的第一個引數是關聯模型的類名。一旦定義了模型關聯，我們就可以使用 `Hyperf` 動態屬性獲得相關的記錄。動態屬性允許你訪問關係方法就像訪問模型中定義的屬性一樣：

```php
$role = User::query()->find(1)->role;
```

### 一對多

『一對多』關聯用於定義單個模型擁有任意數量的其它關聯模型。例如，一個作者可能寫有多本書。正如其它所有的 `Hyperf` 關聯一樣，一對多關聯的定義也是在 `Hyperf` 模型中寫一個方法：

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\DbConnection\Model\Model;

class User extends Model
{
    public function books()
    {
        return $this->hasMany(Book::class, 'user_id', 'id');
    }
}
```

記住一點，`Hyperf` 將會自動確定 `Book` 模型的外來鍵屬性。按照約定，`Hyperf` 將會使用所屬模型名稱的 『snake case』形式，再加上 `_id` 字尾作為外來鍵欄位。因此，在上面這個例子中，`Hyperf` 將假定 `User` 對應到 `Book` 模型上的外來鍵就是 `book_id`。

一旦關係被定義好以後，就可以通過訪問 `User` 模型的 `books` 屬性來獲取評論的集合。記住，由於 Hyperf 提供了『動態屬性』 ，所以我們可以像訪問模型的屬性一樣訪問關聯方法：

```php
$books = User::query()->find(1)->books;

foreach ($books as $book) {
    //
}
```

當然，由於所有的關聯還可以作為查詢語句構造器使用，因此你可以使用鏈式呼叫的方式，在 books 方法上新增額外的約束條件：

```php
$book = User::query()->find(1)->books()->where('title', '一個月精通Hyperf框架')->first();
```

### 一對多（反向）

現在，我們已經能獲得一個作者的所有作品，接著再定義一個通過書獲得其作者的關聯關係。這個關聯是 `hasMany` 關聯的反向關聯，需要在子級模型中使用 `belongsTo` 方法定義它：

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\DbConnection\Model\Model;

class Book extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
```

這個關係定義好以後，我們就可以通過訪問 `Book` 模型的 author 這個『動態屬性』來獲取關聯的 `User` 模型了：

```php
$book = Book::find(1);

echo $book->author->name;
```

### 多對多

多對多關聯比 `hasOne` 和 `hasMany` 關聯稍微複雜些。舉個例子，一個使用者可以擁有很多種角色，同時這些角色也被其他使用者共享。例如，許多使用者可能都有 「管理員」 這個角色。要定義這種關聯，需要三個資料庫表： `users`，`roles` 和 `role_user`。`role_user` 表的命名是由關聯的兩個模型按照字母順序來的，並且包含了 `user_id` 和 `role_id` 欄位。

多對多關聯通過呼叫 `belongsToMany` 這個內部方法返回的結果來定義，例如，我們在 `User` 模型中定義 `roles` 方法：

```php
<?php

namespace App;

use Hyperf\DbConnection\Model\Model;

class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

一旦關聯關係被定義後，你可以通過 `roles` 動態屬性獲取使用者角色：

```php
$user = User::query()->find(1);

foreach ($user->roles as $role) {
    //
}
```

當然，像其它所有關聯模型一樣，你可以使用 `roles` 方法，利用鏈式呼叫對查詢語句新增約束條件：

```php
$roles = User::find(1)->roles()->orderBy('name')->get();
```

正如前面所提到的，為了確定關聯連線表的表名，`Hyperf` 會按照字母順序連線兩個關聯模型的名字。當然，你也可以不使用這種約定，傳遞第二個引數到 belongsToMany 方法即可：

```php
return $this->belongsToMany(Role::class, 'role_user');
```

除了自定義連線表的表名，你還可以通過傳遞額外的引數到 `belongsToMany` 方法來定義該表中欄位的鍵名。第三個引數是定義此關聯的模型在連線表裡的外來鍵名，第四個引數是另一個模型在連線表裡的外來鍵名：

```php
return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
```

#### 獲取中間表字段

就如你剛才所瞭解的一樣，多對多的關聯關係需要一箇中間表來提供支援， `Hyperf` 提供了一些有用的方法來和這張表進行互動。例如，假設我們的 `User` 物件關聯了多個 `Role` 物件。在獲得這些關聯物件後，可以使用模型的 `pivot` 屬性訪問中間表的資料：

```php
$user = User::find(1);

foreach ($user->roles as $role) {
    echo $role->pivot->created_at;
}
```

需要注意的是，我們獲取的每個 `Role` 模型物件，都會被自動賦予 `pivot` 屬性，它代表中間表的一個模型物件，並且可以像其他的 `Hyperf` 模型一樣使用。

預設情況下，`pivot` 物件只包含兩個關聯模型的主鍵，如果你的中間表裡還有其他額外欄位，你必須在定義關聯時明確指出：

```php
return $this->belongsToMany(Role::class)->withPivot('column1', 'column2');
```

如果你想讓中間表自動維護 `created_at` 和 `updated_at` 時間戳，那麼在定義關聯時附加上 `withTimestamps` 方法即可：

```php
return $this->belongsToMany(Role::class)->withTimestamps();
```

#### 自定義 `pivot` 屬性名稱

如前所述，來自中間表的屬性可以使用 `pivot` 屬性訪問。但是，你可以自由定製此屬性的名稱，以便更好的反應其在應用中的用途。

例如，如果你的應用中包含可能訂閱的使用者，則使用者與部落格之間可能存在多對多的關係。如果是這種情況，你可能希望將中間表訪問器命名為 `subscription` 取代 `pivot` 。這可以在定義關係時使用 `as` 方法完成：

```php
return $this->belongsToMany(Podcast::class)->as('subscription')->withTimestamps();
```

一旦定義完成，你可以使用自定義名稱訪問中間表資料：

```php
$users = User::with('podcasts')->get();

foreach ($users->flatMap->podcasts as $podcast) {
    echo $podcast->subscription->created_at;
}
```

#### 通過中間表過濾關係

在定義關係時，你還可以使用 `wherePivot` 和 `wherePivotIn` 方法來過濾 `belongsToMany` 返回的結果：

```php
return $this->belongsToMany('App\Role')->wherePivot('approved', 1);

return $this->belongsToMany('App\Role')->wherePivotIn('priority', [1, 2]);
```


## 預載入

當以屬性方式訪問 `Hyperf` 關聯時，關聯資料「懶載入」。這著直到第一次訪問屬性時關聯資料才會被真實載入。不過 `Hyperf` 能在查詢父模型時「預先載入」子關聯。預載入可以緩解 N + 1 查詢問題。為了說明 N + 1 查詢問題，考慮 `User` 模型關聯到 `Role` 的情形：

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Hyperf\DbConnection\Model\Model;

class User extends Model
{
    public function role()
    {
        return $this->hasOne(Role::class, 'user_id', 'id');
    }
}
```

現在，我們來獲取所有的使用者及其對應角色

```php
$users = User::query()->get();

foreach ($users as $user){
    echo $user->role->name;
}
```

此迴圈將執行一個查詢，用於獲取全部使用者，然後為每個使用者執行獲取角色的查詢。如果我們有 10 個人，此迴圈將執行 11 個查詢：1 個用於查詢使用者，10 個附加查詢對應的角色。

謝天謝地，我們能夠使用預載入將操作壓縮到只有 2 個查詢。在查詢時，可以使用 with 方法指定想要預載入的關聯：

```php
$users = User::query()->with('role')->get();

foreach ($users as $user){
    echo $user->role->name;
}
```

在這個例子中，僅執行了兩個查詢

```
SELECT * FROM `user`;

SELECT * FROM `role` WHERE id in (1, 2, 3, ...);
```
