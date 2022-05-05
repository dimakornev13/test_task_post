<?php

namespace App\Console\Commands;

use App\Modules\PostOffice\Factory\WorldFactory;
use Illuminate\Console\Command;

class SendPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start sending post';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $dailyItemsStream = config('post_office.items');

        $this->runExample($dailyItemsStream);
        $this->runCandidate($dailyItemsStream);

        return 0;
    }

    /**
     * @param array $items
     */
    private function runExample(array $items): void
    {
        $exampleWorld = WorldFactory::createDefaultWorld();

        $exampleWorld->run($items);

        $this->comment('Example post office');
        $this->comment(sprintf('Result: %s', $exampleWorld->getTotalDiscontentIndex()));
        $this->comment(sprintf('Total discontent index: %d.', $exampleWorld->getTotalDiscontentIndex()));
        $this->comment(sprintf('Total discontent users: %d.', $exampleWorld->getTotalDiscontentUsers()));
        $this->comment(sprintf('Total days: %d', $exampleWorld->getCurrentDay()));
        $this->comment(sprintf('Items lost: %d.', $exampleWorld->getItemsInCount() - $exampleWorld->getItemsOutCount()));
    }

    /**
     * @param array $items
     */
    private function runCandidate(array $items): void
    {
        $candidateWorld = WorldFactory::createCandidateWorld();

        $candidateWorld->run($items);

        $this->comment('Candidate post office');

        $totalIndex = $candidateWorld->getTotalDiscontentIndex();

        if($totalIndex <= 7) {
            $this->getOutput()->writeln(sprintf('<fg=#065480>Result: %s</>', 'Unbelievable!!!'));
        }
        elseif($totalIndex <= 10) {
            $this->getOutput()->writeln(sprintf('<fg=green>Result: %s</>', 'Good!'));
        }
        elseif($totalIndex <= 20) {
            $this->getOutput()->writeln(sprintf('<fg=#d4b800>Result: %s</>', 'Normal.'));
        }
        else {
            $this->getOutput()->writeln(sprintf('<fg=red>Result: %s</>', 'Bad'));
        }

        $this->comment(sprintf('Total discontent index: %d.', $candidateWorld->getTotalDiscontentIndex()));
        $this->comment(sprintf('Total discontent users: %d.', $candidateWorld->getTotalDiscontentUsers()));
        $this->comment(sprintf('Total days: %d', $candidateWorld->getCurrentDay()));
        $this->comment(sprintf('Items lost: %d.', $candidateWorld->getItemsInCount() - $candidateWorld->getItemsOutCount()));
    }
}
