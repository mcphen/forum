<?php

class Topics
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
     * @param $url
     * @return string
     */
    public function urlCatTopic($url)
    {
        return 'index.php?p=posts.categoriestopics&cat='.$url;
    }

    public function urlSubTopic($url)
    {
        return 'index.php?p=posts.categoriestopics&sub='.$url;
    }

    public function urlViewTopic($url)
    {
        return 'index.php?p=view.topics&topic='.$url;
    }

    public function urlReqTopic($url)
    {
        return 'index.php?p=req.topics&topic='.$url;
    }

    /**
     * @param $subtopics
     * @param bool $one
     * @return array|bool|int|mixed
     */
    public function countSubTopics($subtopics, $one=false)
    {
        $row=0;
        $req= $this->db->prepare("SELECT * FROM f_topics_categories 
                                  INNER JOIN  f_topics                                 
                                  ON f_topics_categories.idf_topics=f_topics.idf_topics
                                  INNER JOIN users
                                  ON f_topics.id_createur=users.idusers
                                  WHERE idf_categories=? ORDER BY f_topics.idf_topics DESC ",
            [$subtopics]);

        if($one){
             return $req;
        }else{
            return count($req);
        }
    }

    public  function messagerie($auth, $c=null){
        if ($c){
            $req= $this->db->prepare("SELECT * FROM `f_messageries` WHERE etatf=0 AND id_destinataire=?", [$auth]);
            return count($req);

        }else{
            return $this->db->prepare("SELECT * FROM `f_messageries` INNER JOIN users ON f_messageries.id_expediteur=users.idusers WHERE id_destinataire=? ORDER BY idf_messageries DESC", [$auth]);

        }
    }

    public  function notifications($auth, $c=null){
        if ($c){
            $req= $this->db->prepare("SELECT * FROM `f_notifications` WHERE lu=0 AND idusers=?", [$auth]);
            return count($req);

        }else{
            return $this->db->prepare("SELECT * FROM `f_notifications` INNER JOIN users ON f_notifications.idusers=users.idusers WHERE f_notifications.idusers=?", [$auth]);

        }
    }
    /**
     * @return array|mixed|PDOStatement
     * retourne le nombre de colonne dans la table topics
     */
    public function counttopics($auth=null){
        if ($auth){
            return $this->db->prepare("SELECT COUNT(*) as nbretopics FROM f_topics WHERE id_createur=?",[$auth], null, true);
        }else{
            return $this->db->query("SELECT COUNT(*) as nbretopics FROM f_topics", null, true);
        }


    }

    /**
     * @param $subtopics
     * @param bool $one
     * @param null $usr
     * @return array|bool|int|mixed
     * retourne si @param $usr existe les sujets suivis par un utilisateur.
     * sinon il retourne les sujets appartenant à une sous categorie si @param $one est vrai sinon elle retourne le nombre de colonne
     */
    public function subTopics($subtopics, $one=false, $usr=NULL)
    {
        if ($usr){
            return $this->db->prepare("SELECT * FROM f_topics_categories 
                                      INNER JOIN f_topics 
                                      ON f_topics_categories.idf_topics=f_topics.idf_topics 
                                      INNER JOIN users 
                                      ON f_topics.id_createur=users.idusers 
                                      INNER JOIN f_suivis 
                                      ON f_suivis.idf_topics=f_topics.idf_topics 
                                      INNER JOIN f_sous_categories
                                      ON f_sous_categories.idf_sous_categories=f_topics_categories.idf_sous_categories
                                      WHERE id_users=? ORDER BY idf_suivis DESC",
                [$subtopics]);
        }else{
            $req= $this->db->prepare("SELECT * FROM f_topics_categories 
                                  INNER JOIN  f_topics
                                  ON f_topics_categories.idf_topics=f_topics.idf_topics
                                  INNER JOIN users
                                  ON f_topics.id_createur=users.idusers
                                  WHERE idf_sous_categories=? ORDER BY f_topics.idf_topics DESC ",
                [$subtopics]);

            if($one){
                return $req;
            }else{
                return count($req);
            }
        }



    }

    /**
     * @param $idUrl
     * @return array|bool|mixed
     * retourne les données(les données utilisateurs, sous-catégorie, categorie, sujet) liées à un sujet.
     */

    public function joinCats($idUrl){
        return $this->db->prepare("SELECT * FROM f_topics_categories AS ftc
                                   INNER JOIN f_sous_categories AS fsc
                                   ON ftc.idf_sous_categories= fsc.idf_sous_categories
                                   INNER JOIN f_categories AS fc
                                   ON ftc.idf_categories=fc.idf_categories 
                                   INNER JOIN f_topics AS ft
                                   ON ftc.idf_topics=ft.idf_topics
                                   INNER JOIN users
                                   ON ft.id_createur=users.idusers
                                   WHERE ftc.idf_topics=?", [$idUrl]
        );

    }




}