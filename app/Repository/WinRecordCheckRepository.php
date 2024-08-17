<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class WinRecordCheckRepository
{
    protected $record, $filter, $type;

    public function __construct(
        protected $data
    ) {
        $this->filter = $data['filter'];
    }

    public function execute()
    {

        $query = DB::table("win_records")

            ->join('users', 'users.id', 'win_records.user_id')

            ->select(
                'win_records.*',
                'users.user_id as user_ids'
            )

            ->whereIn('win_records.type', ['2D', '3D', 'Body', 'Maung'])

            ->where('win_records.status', '!=' , 2 )

            ->when(request()->input('agent_id') ?? NULL, function ($q) {
                return $q->whereIn('agent_id', request()->input('agent_id'));
            })

            ->when(request()->input('user_id') ?? NULL, function ($q) {
                return $q->where('users.user_id', request()->input('user_id'));
            })

            ->when(request()->input('type') ?? NULL, function ($q) {
                return $q->where('win_records.type', request()->input('type'));
            })

            ->when(request()->input('start_date') ?? NULL, function ($q) {
                return $q->whereDate('win_records.created_at', '>=', request()->input('start_date'));
            })

            ->when(request()->input('end_date') ?? NULL, function ($q) {
                return $q->whereDate('win_records.created_at', '<=', request()->input('end_date'));
            })

            ->orderBy('win_records.user_id')

            ->orderBy('win_records.created_at', 'desc');

        return $query;
    }

    protected function filterQuery($query)
    {
        return $query->where('type', '2D')

            ->when($this->filter['agent_id'] ?? NULL, function ($q) {
                return $q->whereIn('agent_id', $this->filter['agent_id']);
            })

            ->when($this->filter['start_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '>=', $this->filter['start_date']);
            })

            ->when($this->filter['end_date'] ?? NULL, function ($q) {
                return $q->whereDate('created_at', '<=', $this->filter['end_date']);
            });
    }
}
