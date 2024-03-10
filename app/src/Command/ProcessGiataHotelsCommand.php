<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\DataFetchingService;
use App\Service\DataCachingService;
use App\Service\DataProcessingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:process-giata-hotels',
    description: 'This command allows you to process the GIATA Drive Hotel Directory.'
)]
class ProcessGiataHotelsCommand extends Command
{
    public function __construct(
        private readonly DataFetchingService   $dataFetchingService,
        private readonly DataCachingService    $dataCachingService,
        private readonly DataProcessingService $dataProcessingService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('jsonDataUrl', InputArgument::REQUIRED, 'The URL of the JSON data to process');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsonDataUrl = $input->getArgument('jsonDataUrl');
        $io = new SymfonyStyle($input, $output);
        $io->note('Dispatching jobs to fetch and cache hotel data...');

        $urls = $this->dataFetchingService->fetchHotelsUrlList($jsonDataUrl);

        $totalCount = 0;

        $progressBar = $io->createProgressBar(count($urls));
        $progressBar->start();

        foreach (array_chunk($urls, 3000) as $urlChunk) {
            $cachedUrlsChunk = $this->dataCachingService->fetchCachedDataBatch($urlChunk);
            $processedData = $this->dataProcessingService->processCachedData($cachedUrlsChunk);
            $totalCount += count($processedData);

            $progressBar->advance(count($urlChunk));
        }

        $progressBar->finish();

        $io->success(sprintf(
            "Processed GIATA Drive Hotel Directory. Hotels with GIATA-ID divisible by rating: %d",
            $totalCount
        ));

        return Command::SUCCESS;
    }

}
