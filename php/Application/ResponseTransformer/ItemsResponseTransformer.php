<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\ResponseModel\ItemResponseModel;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ItemsResponseTransformer implements ItemsResponseTransformerInterface
{
    use ResponseTransformerListTrait;

    private $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = new PropertyAccessor();
    }

    /**
     * @param array $items
     * @param string $languageCode
     * @return ItemResponseModel[]
     */
    public function transform(array $items, string $languageCode): array
    {
        return array_map(
            function($document) use ($languageCode) {
                $itemModel = new ItemResponseModel();

                $this->setValue($itemModel, $document, 'id');
                $this->setValue($itemModel, $document, 'id_int');

                $this->setLanguageValue($itemModel, $document, $languageCode, 'name');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'name_dirify');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'categories', 'category');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'categories_name_dirify');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'downloads', 'descargas');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'publication_flags', 'est_publicacion');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'item_country_code');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'nr_votes_all');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'permissions', 'permisos');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'sentence');
                $this->setLanguageValue($itemModel, $document, $languageCode, 'native_price', 'precio_nativo');

                // Flags calculated on Solr side
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_edited');          // Always 0 in Solr configuration
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_public_list');     // 1 && !4 && 256 && !512 && !2048 && (!64 || 128) (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_public_only_apk'); // 1 && !4 && 64 && 128 && 256 && !512 && !2048

                // Main flags with small additional checks on Solr side
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_public');       // 1 && 256 && 512 (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_validated');    // 2               (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_deleted');      // 4 && 2048       (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_duplicated');   // 8               (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_promoted');     // 16              (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_link_broken', 'is_linkbroke'); // 64 (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_relevant');     // 16384           (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_trending');     // 32768           (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_deal');         // 65536           (Solr config)
                $this->setLanguageValue($itemModel, $document, $languageCode, 'is_top_download'); // 131072          (Solr config)

                // Fill missing publication flags fields
                $itemModel->setIsNewsletter(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_NEWSLETTER) > 0);      // 32
                $itemModel->setIsHasApk(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_HAS_APK) > 0);             // 128
                $itemModel->setIsShowAdsense(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_SHOW_ADSENSE) > 0);   // 256
                $itemModel->setIsNoIndex(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_NO_INDEX) > 0);           // 512
                $itemModel->setIsUptodown(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_UPTODOWN) > 0);          // 1024
                $itemModel->setIsForce404(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_FORCE_404) > 0);         // 2048
                $itemModel->setIsManualUpload(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_MANUAL_UPLOAD) > 0); // 4096
                $itemModel->setIsRunApks(($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_MANUAL_UPLOAD) > 0);      // 8192

                $this->setValue($itemModel, $document, 'apk_size');
                $this->setValue($itemModel, $document, 'apk_version');
                $this->setValue($itemModel, $document, 'apks');
                $this->setValue($itemModel, $document, 'avg_note');
                $this->setValue($itemModel, $document, 'country_code');
                $this->setValue($itemModel, $document, 'recent_changes', 'cambios_recientes');
                $this->setValue($itemModel, $document, 'categories_id');
                $this->setValue($itemModel, $document, 'categories_parent_id', 'category_parent_id');
                $this->setValue($itemModel, $document, 'email_developer', 'email_desarrollador');
                $this->setValue($itemModel, $document, 'google_id_dirify');
                $this->setValue($itemModel, $document, 'main_image', 'imagen_principal');
                $this->setValue($itemModel, $document, 'images', 'imagenes');
                $this->setValue($itemModel, $document, 'important_developer');
                $this->setValue($itemModel, $document, 'integrated_purchases');
                $this->setValue($itemModel, $document, 'interface_languages');
                $this->setValue($itemModel, $document, 'minimum_version');
                $this->setValue($itemModel, $document, 'name_dirify_langs');
                $this->setValue($itemModel, $document, 'nr_comments');
                $this->setValue($itemModel, $document, 'nr_views');
                $this->setValue($itemModel, $document, 'nr_votes');
                $this->setValue($itemModel, $document, 'amount_of_valuations', 'cantidad_de_valoraciones');
                $this->setValue($itemModel, $document, 'pegi');
                $this->setValue($itemModel, $document, 'platforms');
                $this->setValue($itemModel, $document, 'price', 'precio');
                $this->setValue($itemModel, $document, 'discount_price', 'precio_descuento');
                $this->setValue($itemModel, $document, 'project_id');
                $this->setValue($itemModel, $document, 'provider', 'proveedor');
                $this->setValue($itemModel, $document, 'provider_dirify', 'proveedor_dirify');
                $this->setValue($itemModel, $document, 'ranking');
                $this->setValue($itemModel, $document, 'score');
                $this->setValue($itemModel, $document, 'size', 'tamano');
                $this->setValue($itemModel, $document, 'type_id');
                $this->setValue($itemModel, $document, 'url_developer', 'url_desarrollador');
                $this->setValue($itemModel, $document, 'url_developer_google', 'url_desarrollador_google');
                $this->setValue($itemModel, $document, 'url_developer_download');
                $this->setValue($itemModel, $document, 'url_official', 'url_oficial');
                $this->setValue($itemModel, $document, 'version');
                $this->setValue($itemModel, $document, 'video_principal');
                $this->setValue($itemModel, $document, 'web');

                $this->setDateTimeValue($itemModel, $document, 'created_at');
                $this->setDateTimeValue($itemModel, $document, 'updated_at');
                $this->setDateTimeValue($itemModel, $document, 'upload_date', 'fecha_subida');

                $itemModel->setIsOnlyApk(
                    ($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_HAS_APK) > 0 &&
                    ($itemModel->getPublicationFlags() & ItemResponseModel::PUBLICATION_LINK_BROKEN) > 0 &&
                    (null === $itemModel->getPrice() || 0 === $itemModel->getPrice() || 0.0 === $itemModel->getPrice())
                );

                if (count($itemModel->getCategories())) {
                    $categoriesNames = $itemModel->getCategories();
                    $itemModel->setCategory(end($categoriesNames));
                }

                return $itemModel;
            },
            $items
        );
    }

    private function setLanguageValue(ItemResponseModel $itemModel, $document, string $languageCode, string $rawKey, ?string $oldKey = null): void
    {
        $keyToSubstitute = $oldKey ?: $rawKey;
        $languageKey = sprintf('%s_%s', $keyToSubstitute, $languageCode);
        if ($this->propertyAccessor->isReadable($document, $languageKey) && $this->propertyAccessor->isWritable($itemModel, $rawKey)) {
            $this->propertyAccessor->setValue($itemModel, $rawKey, $this->propertyAccessor->getValue($document, $languageKey));
        }
    }

    private function setValue(ItemResponseModel $itemModel, $document, string $key, ?string $oldKey = null): void
    {
        $oldKey = $oldKey ?: $key;
        if ($this->propertyAccessor->isReadable($document, $oldKey) && $this->propertyAccessor->isWritable($itemModel, $key)) {
            $this->propertyAccessor->setValue($itemModel, $key, $this->propertyAccessor->getValue($document, $oldKey));
        }
    }

    public function setDateTimeValue(ItemResponseModel $itemModel, $document, string $key, ?string $oldKey = null): void
    {
        $oldKey = $oldKey ?: $key;
        if ($this->propertyAccessor->isReadable($document, $oldKey) && $this->propertyAccessor->isWritable($itemModel, $key)) {
            $oldDateString = $this->propertyAccessor->getValue($document, $oldKey);

            if (null !== $oldDateString) {
                try {
                    $dateValue = new \DateTime($oldDateString);
                } catch (\Exception $ex) {
                    $dateValue = null;
                }

                $this->propertyAccessor->setValue($itemModel, $key, $dateValue);
            }
        }
    }
}
