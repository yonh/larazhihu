# 命令记录
```shell
# 创建控制器
php artisan make:controller QuestionsController

# 直接执行测试文件
phpunit tests/Feature/ViewQuestionsTest.php 

# --filter 后面是测试的方法名
phpunit --filter user_can_view_questions

# 建立 Question 模型，并同时生成数据库迁移文件：
php artisan make:model Question -m

# 建立模型工厂
php artisan make:factory QuestionFactory -m Question

# 创建权限策略
php artisan make:policy QuestionPolicy --model=Question

# 查看当前使用的镜像源
yarn config get registry
# 使用阿里云源
yarn config set registry https://registry.npm.taobao.org/

# 运行 mix 编译命令：
npm run watch-poll

# 运行数据库迁移命令
php artisan migrate

```


使用 tikner 构造数据
```
php artisan tinker


## 创建数据
>>> $question = create(\App\Question::class, ['published_at' => \Carbon\Carbon::now()]);
>>> create(\App\Answer::class, ['question_id' => $question->id], 10);
```
