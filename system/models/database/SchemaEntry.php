<?php


namespace System\Models\Database;


class SchemaEntry
{
    protected string $columnName;
    protected string $dataType;
    protected $defaultValue;
    protected bool $notNull;
    protected bool $primaryKey;

    /**
     * SchemaEntry constructor.
     * @param string $columnName
     * @param string $dataType
     * @param $defaultValue
     * @param bool $notNull
     * @param bool $primaryKey
     */
    public function __construct(string $columnName, string $dataType, $defaultValue, bool $notNull, bool $primaryKey)
    {
        $this->columnName = $columnName;
        $this->dataType = $dataType;
        $this->defaultValue = $defaultValue;
        $this->notNull = $notNull;
        $this->primaryKey = $primaryKey;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     */
    public function setColumnName(string $columnName): void
    {
        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     */
    public function setDataType(string $dataType): void
    {
        $this->dataType = $dataType;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        if (is_string($this->defaultValue)) {
            return "'" . $this->defaultValue . "'";
        }
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return bool
     */
    public function isNotNull(): bool
    {
        return $this->notNull;
    }

    /**
     * @param bool $notNull
     */
    public function setNotNull(bool $notNull): void
    {
        $this->notNull = $notNull;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey(): bool
    {
        return $this->primaryKey;
    }

    /**
     * @param bool $primaryKey
     */
    public function setPrimaryKey(bool $primaryKey): void
    {
        $this->primaryKey = $primaryKey;
    }


}