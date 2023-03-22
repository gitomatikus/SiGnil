<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class ChooseQuestion extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var string
     */
    public $round;
    /**
     * @var string
     */
    public $theme;
    /**
     * @var string
     */
    public $question;
    /**
     * @var int
     */
    private $game;

    /**
     * ChooseQuestion constructor.
     * @param int $game
     * @param string $round
     * @param string $theme
     * @param string $question
     */
    public function __construct(int $game, ?string $round, ?string $theme, ?string $question)
    {
        $this->round = $round;
        $this->theme = $theme;
        $this->question = $question;
        $this->game = $game;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('game.' . $this->game);
    }
}
