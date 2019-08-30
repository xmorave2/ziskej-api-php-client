<?php declare(strict_types = 1);

namespace Mzk\ZiskejApi\ResponseModel;

class Reader
{

    /**
     * Ziskej ID
     *
     * @var string
     */
    private $readerId;

    /**
     * Is able to use Ziskej
     *
     * @var bool|null
     */
    private $isActive = null;

    /**
     * Firstname
     *
     * @var string|null
     */
    private $firstName = null;

    /**
     * Lastname
     * @var string|null
     */
    private $lastName = null;

    /**
     * Email address
     *
     * @var string|null //@todo refactor to email object
     */
    private $email = null;

    /**
     * zda posílat notifikace
     *
     * @var bool|null
     */
    private $notificationEnabled = null;

    /**
     * sigla mateřské knihovny
     *
     * @var string|null
     */
    private $sigla = null;

    /**
     * souhlas s registrací
     *
     * @var bool|null
     */
    private $isGdprReg;

    /**
     * souhlas s uložením dat
     *
     * @var bool|null
     */
    private $isGdprData;

    /**
     * Cound of tickets
     *
     * @var int|null
     */
    private $countTickets = null;

    /**
     * Count of open tickets
     *
     * @var int|null
     */
    private $countTicketsOpen = null;

    /**
     * Count of messages
     *
     * @var int|null
     */
    private $countMessages = null;

    /**
     * Count of unread messages
     *
     * @var int|null
     */
    private $countMessagesUnread = null;

    public function __construct(
        string $reader_id,
        bool $is_gdpr_reg,
        bool $is_gdpr_data
    ) {
        $this->readerId = $reader_id;
        $this->isGdprReg = $is_gdpr_reg;
        $this->isGdprData = $is_gdpr_data;
    }


    /**
     * @param mixed[] $input
     * @return \Mzk\ZiskejApi\ResponseModel\Reader
     */
    public static function fromArray(array $input): self
    {
        $model = new self(
            (string)$input['reader_id'],
            (bool)$input['is_gdpr_reg'],
            (bool)$input['is_gdpr_data']
        );

        $model->isActive = !empty($input['is_active']) ? (bool)$input['is_active'] : null;
        $model->firstName = !empty($input['first_name']) ? (string)$input['first_name'] : null;
        $model->lastName = !empty($input['last_name']) ? (string)$input['last_name'] : null;
        $model->email = !empty($input['email']) ? (string)$input['email'] : null;
        $model->notificationEnabled = !empty($input['notification_enabled'])
            ? (bool)$input['notification_enabled'] : null;
        $model->sigla = !empty($input['sigla']) ? (string)$input['sigla'] : null;
        $model->countTickets = !empty($input['count_tickets']) ? (int)$input['count_tickets'] : null;
        $model->countTicketsOpen = !empty($input['count_tickets_open']) ? (int)$input['count_tickets_open'] : null;
        $model->countMessages = !empty($input['count_messages']) ? (int)$input['count_messages'] : null;
        $model->countMessagesUnread = !empty($input['count_messages_unread'])
            ? (int)$input['count_messages_unread'] : null;

        return $model;
    }

    public function getReaderId(): string
    {
        return $this->readerId;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function isNotificationEnabled(): ?bool
    {
        return $this->notificationEnabled;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function isGdprReg(): ?bool
    {
        return $this->isGdprReg;
    }

    public function isGdprData(): ?bool
    {
        return $this->isGdprData;
    }

    public function getCountTickets(): ?int
    {
        return $this->countTickets;
    }

    public function getCountTicketsOpen(): ?int
    {
        return $this->countTicketsOpen;
    }

    public function getCountMessages(): ?int
    {
        return $this->countMessages;
    }

    public function getCountMessagesUnread(): ?int
    {
        return $this->countMessagesUnread;
    }

}
