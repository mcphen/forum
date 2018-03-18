<?php


class Categories
{
    private $db;

    /**
     * DbAuth constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @return array|mixed|PDOStatement
     * affiche toutes les données de la table f_categories
     */
    public function queryCategories(){
        return $this->db->query("SELECT * FROM f_categories");
    }

    /**
     * @param $urlCat
     * @return array|bool|mixed
     * affiche une colonne de la table f_categories
     */
    public function prepareCategories($urlCat){
        return $this->db->prepare("SELECT * FROM f_categories WHERE idf_categories=?", [$urlCat]);
    }


    /**
     * @param $url
     * @return array|bool|mixed
     * affiche une colonne de la table f_sous_categories
     */
    public function prepareSub($url){
        return $this->db->prepare("SELECT * FROM f_sous_categories WHERE idf_sous_categories=?", [$url]);
    }

    /**
     * afffiche les sous-categories appartenant aux differentes catégories
     * @param $params
     * @return array|bool|mixed
     */
    public function querySubcategories($params)
    {
        return $this->db->prepare("SELECT * FROM f_sous_categories WHERE idf_categories=?", [$params]);
    }

    /**
     * @return string
     * @param $id INT
     * gere les url des sous categories
     */

    public function UrlSubCat($id){
        return 'index.php?p=posts.souscategories&sub='.$id;
    }

    /**
     * @param $id
     * @return string
     * gere les url des categories
     */
    public function UrlCat($urls){
        return 'index.php?p=posts.categories&cat='.$urls;
    }

    /**
     * @param $idUrl
     * @return array|bool|mixed
     * affiche les topics liés aux differents sous-catégories
     */
    public function joinCat($idUrl){
        return $this->db->prepare("SELECT * FROM f_sous_categories 
                                   INNER JOIN f_categories 
                                   ON f_sous_categories.idf_categories=f_categories.idf_categories 
                                   WHERE f_sous_categories.idf_sous_categories=?", [$idUrl]
                                 );

    }

}