<?php namespace Anomaly\WysiwygFieldType\Table;

use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UploadTableBuilder
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\WysiwygFieldType\Table
 */
class UploadTableBuilder extends TableBuilder
{

    /**
     * The table mode.
     *
     * @var string
     */
    protected $mode = 'file';

    /**
     * The uploaded IDs.
     *
     * @var array
     */
    protected $uploaded = [];

    /**
     * The ajax flag.
     *
     * @var bool
     */
    protected $ajax = true;

    /**
     * The table model.
     *
     * @var string
     */
    protected $model = FileModel::class;

    /**
     * The table filters.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [
        'entry.preview' => [
            'heading' => 'anomaly.module.files::field.preview.name'
        ],
        'name'          => [
            'sort_column' => 'name',
            'wrapper'     => '
                    <strong>{value.file}</strong>
                    <br>
                    <small class="text-muted">{value.disk}://{value.folder}/{value.file}</small>
                    <br>
                    <span>{value.size} {value.keywords}</span>',
            'value'       => [
                'file'     => 'entry.name',
                'folder'   => 'entry.folder.slug',
                'keywords' => 'entry.keywords.labels',
                'disk'     => 'entry.folder.disk.slug',
                'size'     => 'entry.size_label'
            ]
        ],
        'size'          => [
            'sort_column' => 'size',
            'value'       => 'entry.readable_size'
        ],
        'mime_type',
        'folder'
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'limit'              => 999,
        'disable_views'      => true,
        'sortable_headers'   => false,
        'no_results_message' => 'anomaly.field_type.wysiwyg::message.no_uploads'
    ];

    /**
     * Fired just before querying
     * for table entries.
     *
     * @param Builder $query
     */
    public function onQuerying(Builder $query)
    {
        $uploaded = $this->getUploaded();

        $query->whereIn('id', $uploaded ?: [0]);

        $query->orderBy('updated_at', 'ASC');
        $query->orderBy('created_at', 'ASC');
    }

    /**
     * Get uploaded IDs.
     *
     * @return array
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set the uploaded IDs.
     *
     * @param array $uploaded
     * @return $this
     */
    public function setUploaded(array $uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Get the mode.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the mode.
     *
     * @param $mode
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }
}
