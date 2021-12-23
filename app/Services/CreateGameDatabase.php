<?php

namespace App\Services;
use DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameDatabase
{

	private $database;
	private $settings;


	public function __construct(string $database, array $settings = [])
	{
		$this->database = $database;
		$this->settings = $settings;
	}


	public function createDatabase()
	{
		$charset = config("database.connections.game.charset");
		$collation = config("database.connections.game.collation");
		DB::statement("CREATE DATABASE " . $this->database . " CHARACTER SET " . $charset . " COLLATE " . $collation . ";");
		return true;
	}


	public function createTables()
	{
		config(["database.connections.game.database" => $this->database]);
		config(["database.default" => 'game']);

		$this->createSettingsTable();
		$this->createGroupsTable();
		$this->createPlayersTable();
		$this->createBlocksTable();
		$this->createAttachmentsTable();
		$this->createGroupsBlocksTable();
		$this->createShowConditionsTable();
		$this->createQuestionsTable();
		$this->createAnswersTable();
		$this->createAutomaticChecksTable();
		$this->createLocationsTable();

		$this->insertDefaultSettings();
	}


	/**
	 * Создание таблицы "Настройки"
	 */
	public function createSettingsTable()
	{
		Schema::create('settings', function (Blueprint $table) {
			$table->string('name')->primary();
			$table->text('value');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Группы"
	 */
	public function createGroupsTable()
	{
		Schema::create('groups', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->text('description')->nullable()->default(null);
			$table->integer('sort')->default(0);
			$table->timestamp('start_at')->nullable()->default(null);
			$table->timestamp('finish_at')->nullable()->default(null);	
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Участники"
	 */
	public function createPlayersTable()
	{
		Schema::create('players', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('group_id')->nullable()->default(null)->constrained('groups', 'id')->onDelete('set null');
			$table->string('name')->nullable()->default(null);
			$table->string('login')->unique()->nullable()->default(null);
			$table->string('password')->nullable()->default(null);
			$table->string('email')->unique()->nullable()->default(null);
			$table->bigInteger('phone')->nullable()->default(null);
			$table->string('picture')->nullable()->default(null);
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Игровые блоки"
	 */
	public function createBlocksTable()
	{
		Schema::create('blocks', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->text('content')->nullable()->default(null);
			$table->integer('sort')->default(0)->comment('Порядок в списке');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Аттачменты к игровым блокам"
	 */
	public function createAttachmentsTable()
	{
		Schema::create('attachments', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('block_id')->constrained('blocks', 'id')->onDelete('restrict');
			$table->enum('type', ['image', 'video', 'file', 'embedded']);
			$table->string('description')->nullable()->default(null);
			$table->text('content')->nullable()->default(null);
			$table->integer('sort')->default(0)->comment('Порядок в списке');
			$table->bigInteger('size')->default(0)->comment('Размер Файла');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Блоки в группах" (дистанции)
	 */
	public function createGroupsBlocksTable()
	{
		Schema::create('groups_blocks', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('group_id')->constrained('groups', 'id')->onDelete('restrict');
			$table->foreignId('block_id')->constrained('blocks', 'id')->onDelete('restrict');
			$table->integer('sort')->default(0)->comment('Порядок в списке');
		});
	}


	/**
	 * Создание таблицы "Условия показа игрового блока"
	 */
	public function createShowConditionsTable()
	{
		Schema::create('show_conditions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('block_id')->constrained('blocks', 'id')->onDelete('cascade');			
			$table->timestamp('start_at')->nullable()->default(null)->comment('Время открытия вопроса');
			$table->timestamp('finish_at')->nullable()->default(null)->comment('Время закрытия вопроса');
			$table->text('geo_polygon')->nullable()->default(null)->comment('GeoJSON полигона');
			$table->foreignId('target_block_id')->nullable()->default(null)->constrained('blocks', 'id')->onDelete('set null')->comment('ID блока на который нужно дать верный ответ');
			$table->double('target_block_score')->nullable()->default(null)->comment('Балл за целевой блок, необходимый для открытия этого блока');
			$table->double('summary_score')->nullable()->default(null)->comment('Суммарный балл, необходимый для открытия блока');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Вопросы блока"
	 */
	public function createQuestionsTable()
	{
		Schema::create('questions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('block_id')->constrained('blocks', 'id')->onDelete('cascade');
			$table->enum('type', ['text', 'picture', 'location', 'etag']);
			$table->string('label')->nullable()->default(null);
			$table->text('scores')->nullable()->default(null)->comment('Возможные оценки за выполнение задания (-12,0,1,5,10,100)');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Ответы на вопросы блока"
	 */
	public function createAnswersTable()
	{
		Schema::create('answers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('question_id')->constrained('questions', 'id')->onDelete('cascade');
			$table->foreignId('player_id')->constrained('players', 'id')->onDelete('cascade');
			$table->text('content');
			$table->double('score')->nullable()->default(null);
			$table->boolean('checked_automaticly')->default(false)->comment('Кем выставлен балл? 1=Роботом, 0=Жюри');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Автоматических проверок"
	 */
	public function createAutomaticChecksTable()
	{
		Schema::create('automatic_checks', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('question_id')->constrained('questions', 'id')->onDelete('cascade');
			$table->text('content')->nullable()->default(null)->comment('Содержимое для проверки ответа (текст, геополигон или etag)'); // в случае текста - текст, локации - полигон попадания, етаг - етаг, картинка - нулл
			$table->double('score')->comment('Автоматически выставляемый балл в случае успеха автопроверки');
			$table->integer('priority')->default(0)->comment('Порядок в списке автоматических проверок');
			$table->timestamps();
		});
	}


	/**
	 * Создание таблицы "Локации игроков"
	 */
	public function createLocationsTable()
	{
		Schema::create('locations', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('player_id')->constrained('players', 'id')->onDelete('cascade');
			$table->double('lat', 15, 12);
			$table->double('lon', 15, 12);
			$table->bigInteger('created_at');
		});
	}


	/**
	 * Добавление настроек по умолчанию
	 */
	public function insertDefaultSettings()
	{
		$settings = [];
		$settings[]= ['name' => 'engine_version', 'value' => config('app.version')];

		foreach ($this->settings as $name => $value) {
			$settings[]= [
				'name' => $name,
				'value' => $value
			];
		}
		DB::table('settings')->insert($settings);
	}


}
