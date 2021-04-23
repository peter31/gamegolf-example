<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class ItemResponseModel implements Response
{
    public const PUBLICATION_PUBLIC = 1;            // estado_publicado
    public const PUBLICATION_VALIDATED = 2;         // estado_revisado
    public const PUBLICATION_DELETED = 4;           // estado_borrado
    public const PUBLICATION_DUPLICATED = 8;        // estado_duplicado
    public const PUBLICATION_PROMOTED = 16;         // estado_destacado
    public const PUBLICATION_NEWSLETTER = 32;       // estado_newsletter
    public const PUBLICATION_LINK_BROKEN = 64;      // estado_url_borrada
    public const PUBLICATION_HAS_APK = 128;         // estado_apk_download
    public const PUBLICATION_SHOW_ADSENSE = 256;    // estado_mostrar_adsense
    public const PUBLICATION_NO_INDEX = 512;        // estado_no_index
    public const PUBLICATION_UPTODOWN = 1024;       // estado_uptodown
    public const PUBLICATION_FORCE_404 = 2048;      // estado_forzar404
    public const PUBLICATION_MANUAL_UPLOAD = 4096;  // estado_manualupload
    public const PUBLICATION_RUN_APKS = 8192;       // estado_runapks
    public const PUBLICATION_RELEVANT = 16384;      // estado_relevant
    public const PUBLICATION_TRENDING = 32768;      // estado_trending
    public const PUBLICATION_DEAL = 65536;          // estado_deals
    public const PUBLICATION_TOP_DOWNLOAD = 131072; // estado_top_download

    /** @var int */
    private $id;
    /** @var int|null */
    private $idInt;
    /** @var string */
    private $name;
    /** @var string */
    private $nameDirify;
    /** @var string|null */
    private $category;
    /** @var string[] */
    private $categories = [];
    /** @var int[] */
    private $categoriesId = [];
    /** @var int[] */
    private $categoriesParentId = [];
    /** @var string[] */
    private $categoriesNameDirify = [];
    /** @var string|null */
    private $countryCode;

    /** @var bool */
    private $isDuplicated;
    /** @var bool */
    private $isDeleted;
    /** @var bool */
    private $isPromoted;
    /** @var bool */
    private $isEdited;
    /** @var bool|null */
    private $isDeal;
    /** @var bool */
    private $isLinkBroken;
    /** @var bool */
    private $isPublic;
    /** @var bool */
    private $isPublicList;
    /** @var bool */
    private $isPublicOnlyApk;
    /** @var bool|null */
    private $isRelevant;
    /** @var bool */
    private $isTopDownload;
    /** @var bool|null */
    private $isTrending;
    /** @var bool */
    private $isValidated;
    /** @var bool|null */
    private $isNewsletter;
    /** @var bool|null */
    private $isHasApk;
    /** @var bool|null */
    private $isShowAdsense;
    /** @var bool|null */
    private $isNoIndex;
    /** @var bool|null */
    private $isUptodown;
    /** @var bool|null */
    private $isForce404;
    /** @var bool|null */
    private $isManualUpload;
    /** @var bool|null */
    private $isRunApks;

    /** @var string[] */
    private $apks = [];
    /** @var int[] */
    private $apkSize = [];
    /** @var string[] */
    private $apkversion = [];
    /** @var float */
    private $avgNote;
    /** @var string|null */
    private $recentChanges;
    /** @var int */
    private $amountOfValuations;

    /** @var \DateTime */
    private $createdAt;
    /** @var \DateTime */
    private $updatedAt;

    /** @var string|null */
    private $emailDeveloper;
    /** @var \DateTime */
    private $uploadDate;
    /** @var string */
    private $googleIdDirify;
    /** @var string|null */
    private $mainImage;
    /** @var string[] */
    private $images = [];
    /** @var bool */
    private $importantDeveloper;
    /** @var bool */
    private $integratedPurchases;
    /** @var string */
    private $interfaceLanguages;
    /** @var string|null */
    private $minimumVersion;
    /** @var int */
    private $nrComments;
    /** @var int */
    private $nrViews;
    /** @var int|null */
    private $nrVotes;
    /** @var string|null */
    private $pegi;
    /** @var string */
    private $platforms;
    /** @var float|null */
    private $price;
    /** @var float|null */
    private $discountPrice;
    /** @var float|null */
    private $nativePrice;
    /** @var int */
    private $projectId;
    /** @var string|null */
    private $provider;
    /** @var string|null */
    private $providerDirify;
    /** @var int */
    private $ranking;
    /** @var float */
    private $score;
    /** @var string|null */
    private $size;
    /** @var int */
    private $typeId;
    /** @var string|null */
    private $urlDeveloper;
    /** @var string|null */
    private $urlDeveloperGoogle;
    /** @var string|null */
    private $urlDeveloperDownload;
    /** @var string|null */
    private $urlOfficial;
    /** @var string|null */
    private $version;
    /** @var string */
    private $videoPrincipal;
    /** @var string */
    private $web;
    /** @var string */
    private $nameDirifyLangs;
    /** @var int|null */
    private $downloads;
    /** @var int|null */
    private $publicationFlags;
    /** @var string|null */
    private $itemCountryCode;
    /** @var int|null */
    private $nrVotesAll;
    /** @var string|null */
    private $permissions;
    /** @var string */
    private $sentence;

    // Additional generated fields
    /** @var string */
    private $description;
    /** @var string */
    private $link;
    /** @var string */
    private $qrCode;
    /** @var bool */
    private $isOnlyApk;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getIdInt(): ?int
    {
        return $this->idInt;
    }

    public function setIdInt(?int $idInt): self
    {
        $this->idInt = $idInt;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getNameDirify(): string
    {
        return $this->nameDirify;
    }

    public function setNameDirify(string $nameDirify): self
    {
        $this->nameDirify = $nameDirify;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function getCategoriesId(): array
    {
        return $this->categoriesId;
    }

    public function setCategoriesId(array $categoriesId): self
    {
        $this->categoriesId = $categoriesId;
        return $this;
    }

    public function getCategoriesNameDirify(): array
    {
        return $this->categoriesNameDirify;
    }

    public function setCategoriesNameDirify(array $categoriesNameDirify): self
    {
        $this->categoriesNameDirify = $categoriesNameDirify;
        return $this;
    }

    public function getCategoriesParentId(): array
    {
        return $this->categoriesParentId;
    }

    public function setCategoriesParentId(array $categoriesParentId): self
    {
        $this->categoriesParentId = $categoriesParentId;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getIsDuplicated(): bool
    {
        return $this->isDuplicated;
    }

    public function setIsDuplicated(bool $isDuplicated): self
    {
        $this->isDuplicated = $isDuplicated;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function isPromoted(): bool
    {
        return $this->isPromoted;
    }

    public function setIsPromoted(bool $isPromoted): self
    {
        $this->isPromoted = $isPromoted;
        return $this;
    }

    public function isEdited(): bool
    {
        return $this->isEdited;
    }

    public function setIsEdited(bool $isEdited): self
    {
        $this->isEdited = $isEdited;
        return $this;
    }

    public function isDeal(): ?bool
    {
        return $this->isDeal;
    }

    public function setIsDeal(?bool $isDeal): self
    {
        $this->isDeal = $isDeal;
        return $this;
    }

    public function isLinkBroken(): bool
    {
        return $this->isLinkBroken;
    }

    public function setIsLinkBroken(bool $isLinkBroken): self
    {
        $this->isLinkBroken = $isLinkBroken;
        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    public function isPublicList(): bool
    {
        return $this->isPublicList;
    }

    public function setIsPublicList(bool $isPublicList): self
    {
        $this->isPublicList = $isPublicList;
        return $this;
    }

    public function isPublicOnlyApk(): bool
    {
        return $this->isPublicOnlyApk;
    }

    public function setIsPublicOnlyApk(bool $isPublicOnlyApk): self
    {
        $this->isPublicOnlyApk = $isPublicOnlyApk;
        return $this;
    }

    public function isRelevant(): ?bool
    {
        return $this->isRelevant;
    }

    public function setIsRelevant(?bool $isRelevant): self
    {
        $this->isRelevant = $isRelevant;
        return $this;
    }

    public function isTopDownload(): bool
    {
        return $this->isTopDownload;
    }

    public function setIsTopDownload(bool $isTopDownload): self
    {
        $this->isTopDownload = $isTopDownload;
        return $this;
    }

    public function isTrending(): ?bool
    {
        return $this->isTrending;
    }

    public function setIsTrending(?bool $isTrending): self
    {
        $this->isTrending = $isTrending;
        return $this;
    }

    public function isValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;
        return $this;
    }

    public function getIsNewsletter(): ?bool
    {
        return $this->isNewsletter;
    }

    public function setIsNewsletter(?bool $isNewsletter): self
    {
        $this->isNewsletter = $isNewsletter;
        return $this;
    }

    public function getIsHasApk(): ?bool
    {
        return $this->isHasApk;
    }

    public function setIsHasApk(?bool $isHasApk): self
    {
        $this->isHasApk = $isHasApk;
        return $this;
    }

    public function getIsShowAdsense(): ?bool
    {
        return $this->isShowAdsense;
    }

    public function setIsShowAdsense(?bool $isShowAdsense): self
    {
        $this->isShowAdsense = $isShowAdsense;
        return $this;
    }

    public function getIsNoIndex(): ?bool
    {
        return $this->isNoIndex;
    }

    public function setIsNoIndex(?bool $isNoIndex): self
    {
        $this->isNoIndex = $isNoIndex;
        return $this;
    }

    public function getIsUptodown(): ?bool
    {
        return $this->isUptodown;
    }

    public function setIsUptodown(?bool $isUptodown): self
    {
        $this->isUptodown = $isUptodown;
        return $this;
    }

    public function getIsForce404(): ?bool
    {
        return $this->isForce404;
    }

    public function setIsForce404(?bool $isForce404): self
    {
        $this->isForce404 = $isForce404;
        return $this;
    }

    public function getIsManualUpload(): ?bool
    {
        return $this->isManualUpload;
    }

    public function setIsManualUpload(?bool $isManualUpload): self
    {
        $this->isManualUpload = $isManualUpload;
        return $this;
    }

    public function getIsRunApks(): ?bool
    {
        return $this->isRunApks;
    }

    public function setIsRunApks(?bool $isRunApks): self
    {
        $this->isRunApks = $isRunApks;
        return $this;
    }

    public function getApks(): array
    {
        return $this->apks;
    }

    public function setApks(?array $apks): self
    {
        if (null !== $apks) {
            $this->apks = $apks;
        }

        return $this;
    }

    public function getApkSize(): array
    {
        return $this->apkSize;
    }

    public function setApkSize(?array $apkSize): self
    {
        if (null !== $apkSize) {
            $this->apkSize = $apkSize;
        }

        return $this;
    }

    public function getApkversion(): array
    {
        return $this->apkversion;
    }

    public function setApkversion(?array $apkversion): self
    {
        if (null !== $apkversion) {
            $this->apkversion = $apkversion;
        }

        return $this;
    }

    public function getAvgNote(): float
    {
        return $this->avgNote;
    }

    public function setAvgNote(float $avgNote): self
    {
        $this->avgNote = $avgNote;
        return $this;
    }

    public function getRecentChanges(): ?string
    {
        return $this->recentChanges;
    }

    public function setRecentChanges(?string $recentChanges): self
    {
        $this->recentChanges = $recentChanges;
        return $this;
    }

    public function getAmountOfValuations(): int
    {
        return $this->amountOfValuations;
    }

    public function setAmountOfValuations(int $amountOfValuations): self
    {
        $this->amountOfValuations = $amountOfValuations;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getEmailDeveloper(): ?string
    {
        return $this->emailDeveloper;
    }

    public function setEmailDeveloper(?string $emailDeveloper): self
    {
        $this->emailDeveloper = $emailDeveloper;
        return $this;
    }

    public function getUploadDate(): \DateTime
    {
        return $this->uploadDate;
    }

    public function setUploadDate(\DateTime $uploadDate): self
    {
        $this->uploadDate = $uploadDate;
        return $this;
    }

    public function getGoogleIdDirify(): string
    {
        return $this->googleIdDirify;
    }

    public function setGoogleIdDirify(string $googleIdDirify): self
    {
        $this->googleIdDirify = $googleIdDirify;
        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;
        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        if (null !== $images) {
            $this->images = $images;
        }

        return $this;
    }

    public function isImportantDeveloper(): ?bool
    {
        return $this->importantDeveloper;
    }

    public function setImportantDeveloper(?bool $importantDeveloper): self
    {
        $this->importantDeveloper = $importantDeveloper;
        return $this;
    }

    public function isIntegratedPurchases(): ?bool
    {
        return $this->integratedPurchases;
    }

    public function setIntegratedPurchases(?bool $integratedPurchases): self
    {
        $this->integratedPurchases = $integratedPurchases;
        return $this;
    }

    public function getInterfaceLanguages(): ?string
    {
        return $this->interfaceLanguages;
    }

    public function setInterfaceLanguages(?string $interfaceLanguages): self
    {
        $this->interfaceLanguages = $interfaceLanguages;
        return $this;
    }

    public function getMinimumVersion(): ?string
    {
        return $this->minimumVersion;
    }

    public function setMinimumVersion(?string $minimumVersion): self
    {
        $this->minimumVersion = $minimumVersion;
        return $this;
    }

    public function getNrComments(): int
    {
        return $this->nrComments;
    }

    public function setNrComments(int $nrComments): self
    {
        $this->nrComments = $nrComments;
        return $this;
    }

    public function getNrViews(): int
    {
        return $this->nrViews;
    }

    public function setNrViews(int $nrViews): self
    {
        $this->nrViews = $nrViews;
        return $this;
    }

    public function getNrVotes(): ?int
    {
        return $this->nrVotes;
    }

    public function setNrVotes(?int $nrVotes): self
    {
        $this->nrVotes = $nrVotes;
        return $this;
    }

    public function getPegi(): ?string
    {
        return $this->pegi;
    }

    public function setPegi(?string $pegi): self
    {
        $this->pegi = $pegi;
        return $this;
    }

    public function getPlatforms(): ?string
    {
        return $this->platforms;
    }

    public function setPlatforms(?string $platforms): self
    {
        $this->platforms = $platforms;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getDiscountPrice(): ?float
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(?float $discountPrice): self
    {
        $this->discountPrice = $discountPrice;
        return $this;
    }

    public function getNativePrice(): ?float
    {
        return $this->nativePrice;
    }

    public function setNativePrice(?float $nativePrice): self
    {
        $this->nativePrice = $nativePrice;
        return $this;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    public function getProviderDirify(): ?string
    {
        return $this->providerDirify;
    }

    public function setProviderDirify(?string $providerDirify): self
    {
        $this->providerDirify = $providerDirify;
        return $this;
    }

    public function getRanking(): int
    {
        return $this->ranking;
    }

    public function setRanking(int $ranking): self
    {
        $this->ranking = $ranking;
        return $this;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): self
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function getUrlDeveloper(): ?string
    {
        return $this->urlDeveloper;
    }

    public function setUrlDeveloper(?string $urlDeveloper): self
    {
        $this->urlDeveloper = $urlDeveloper;
        return $this;
    }

    public function getUrlDeveloperGoogle(): ?string
    {
        return $this->urlDeveloperGoogle;
    }

    public function setUrlDeveloperGoogle(?string $urlDeveloperGoogle): self
    {
        $this->urlDeveloperGoogle = $urlDeveloperGoogle;
        return $this;
    }

    public function getUrlDeveloperDownload(): ?string
    {
        return $this->urlDeveloperDownload;
    }

    public function setUrlDeveloperDownload(?string $urlDeveloperDownload): self
    {
        $this->urlDeveloperDownload = $urlDeveloperDownload;
        return $this;
    }

    public function getUrlOfficial(): ?string
    {
        return $this->urlOfficial;
    }

    public function setUrlOfficial(?string $urlOfficial): self
    {
        $this->urlOfficial = $urlOfficial;
        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function getVideoPrincipal(): ?string
    {
        return $this->videoPrincipal;
    }

    public function setVideoPrincipal(?string $videoPrincipal): self
    {
        $this->videoPrincipal = $videoPrincipal;
        return $this;
    }

    public function getWeb(): string
    {
        return $this->web;
    }

    public function setWeb(string $web): self
    {
        $this->web = $web;
        return $this;
    }

    public function getNameDirifyLangs(): string
    {
        return $this->nameDirifyLangs;
    }

    public function setNameDirifyLangs(string $nameDirifyLangs): self
    {
        $this->nameDirifyLangs = $nameDirifyLangs;
        return $this;
    }

    public function getDownloads(): ?int
    {
        return $this->downloads;
    }

    public function setDownloads(?int $downloads): self
    {
        $this->downloads = $downloads;
        return $this;
    }

    public function getPublicationFlags(): ?int
    {
        return $this->publicationFlags;
    }

    public function setPublicationFlags(?int $publicationFlags): self
    {
        $this->publicationFlags = $publicationFlags;
        return $this;
    }

    public function getItemCountryCode(): ?string
    {
        return $this->itemCountryCode;
    }

    public function setItemCountryCode(?string $itemCountryCode): self
    {
        $this->itemCountryCode = $itemCountryCode;
        return $this;
    }

    public function getNrVotesAll(): ?int
    {
        return $this->nrVotesAll;
    }

    public function setNrVotesAll(?int $nrVotesAll): self
    {
        $this->nrVotesAll = $nrVotesAll;
        return $this;
    }

    public function getPermissions(): ?string
    {
        return $this->permissions;
    }

    public function setPermissions(?string $permissions): self
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function getSentence(): ?string
    {
        return $this->sentence;
    }

    public function setSentence(?string $sentence): self
    {
        $this->sentence = $sentence;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): self
    {
        $this->qrCode = $qrCode;
        return $this;
    }

    public function getIsOnlyApk(): bool
    {
        return $this->isOnlyApk;
    }

    public function setIsOnlyApk(bool $isOnlyApk): self
    {
        $this->isOnlyApk = $isOnlyApk;
        return $this;
    }
}
