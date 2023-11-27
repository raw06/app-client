<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\Shop;
use App\Services\Elasticsearch\ElasticsearchService;
use Illuminate\Console\Command;

class SyncESAllQuestionsForShop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:es-questions {--shop=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $shopName = $this->option('shop');

        $shop = Shop::where('shop', $shopName)->first();

        if (!$shop) {
            $this->line("Shop doesn't exist");
        }

        $questions = Question::where('shop_id', $shop->id)->get();

        if (count($questions) > 0) {
            try {
                /** @var ElasticsearchService $elasticsearchService */
                $elasticsearchService = app(ElasticsearchService::class);
                $elasticsearchService->updateQuestions($questions);

                $this->line("Success synced " . count($questions) . " questions.");
            } catch (\Exception $e) {
                $this->line("Error " . $e);
            }
        } else {
            $this->line("Shop doesn't have any questions to sync");
        }
    }
}
