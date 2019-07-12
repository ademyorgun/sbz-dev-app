{{ $dataTypeContent->appends([
    's' => $search->value,
    'filter' => $search->filter,
    'key' => $search->key,
    'order_by' => $orderBy,
    'sort_order' => $sortOrder,
    'showSoftDeleted' => $showSoftDeleted,
])->links() }}