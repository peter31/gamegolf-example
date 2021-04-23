<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\ResponseModel\ItemResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsResponseTransformerTest extends TestCase
{
    /** @test */
    public function construct()
    {
        $transformer = new ItemsResponseTransformer();
        $this->assertInstanceOf(ItemsResponseTransformerInterface::class, $transformer);
    }

    /** @test */
    public function transformSuccess()
    {
        $document = new \stdClass();
        $document->id = 1;
        $document->id_int = 2;

        $transformer = new ItemsResponseTransformer();
        $result = $transformer->transform([$document], '');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(ItemResponseModel::class, $result[0]);

        /** @var ItemResponseModel $model */
        $model = $result[0];
        $this->assertEquals(1, $model->getId());
        $this->assertEquals(2, $model->getIdInt());
    }

    /** @test */
    public function transformLanguageFields()
    {
        $document = new \stdClass();
        $document->name_en = 'string1';
        $document->name_dirify_en = 'string2';
        $document->category_en = ['string3', 'string4'];
        $document->categories_name_dirify_en = ['string5', 'string6'];
        $document->descargas_en = 100;
        $document->est_publicacion_en = 1;
        $document->item_country_code_en = 'EN';
        $document->nr_votes_all_en = 200;
        $document->permisos_en = 300;
        $document->sentence_en = 'string8';
        $document->precio_nativo_en = 0.0;

        $transformer = new ItemsResponseTransformer();
        $result = $transformer->transform([$document], 'en');

        /** @var ItemResponseModel $model */
        $model = $result[0];
        $this->assertEquals($document->name_en, $model->getName());
        $this->assertEquals($document->name_dirify_en, $model->getNameDirify());
        $this->assertEquals($document->category_en, $model->getCategories());
        $this->assertEquals($document->categories_name_dirify_en, $model->getCategoriesNameDirify());
        $this->assertEquals($document->descargas_en, $model->getDownloads());
        $this->assertEquals($document->est_publicacion_en, $model->getPublicationFlags());
        $this->assertEquals($document->item_country_code_en, $model->getItemCountryCode());
        $this->assertEquals($document->nr_votes_all_en, $model->getNrVotesAll());
        $this->assertEquals($document->permisos_en, $model->getPermissions());
        $this->assertEquals($document->sentence_en, $model->getSentence());
        $this->assertEquals($document->precio_nativo_en, $model->getNativePrice());
    }
}
