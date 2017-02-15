<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;
use App\Models\Poll;
use App\Models\ParticipantVote;

class Option extends Model
{
    protected $fillable = [
        'poll_id',
        'name',
        'image',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function participantVotes()
    {
        return $this->hasMany(ParticipantVote::class);
    }

    public function countVotes()
    {
        $count = config('settings.default_value');
        $this->votes ? $count += $this->votes->count() : '';
        $this->participantVotes ? $count += $this->participantVotes->count() : '';

        return $count;
    }

    public function showListVoterDemo()
    {
        $voters = [];

        foreach ($this->users as $user) {
            $voters[] = [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->getAvatarPath(),
            ];
        }

        foreach ($this->participants as $participant) {
            $voters[] = [
                'name' => $participant->name,
                'email' => $participant->email,
                'avatar' => asset(config('settings.image_default_path')),
            ];
        }

        return array_slice($voters, 0, config('settings.limit_voters_option'));
    }

    public function showImage()
    {
        if ($this->image) {
            return asset(config('settings.option.path_image'). $this->image);
        }

        return asset(config('settings.option.path_image_default'));
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'participant_votes');
    }
}
