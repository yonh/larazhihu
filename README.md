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
```