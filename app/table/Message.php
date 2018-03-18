<?php
class Message
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

    public function countmsg($auth=null){
        if ($auth){
            return $this->db->prepare("SELECT COUNT(*) AS nbremsg FROM f_messages WHERE id_posteur=?",[$auth],NULL, true);

        }else{
            return $this->db->query("SELECT COUNT(*) AS nbremsg FROM f_messages", NULL, true);
        }

    }
    /**
     * @param $cat
     * @return array|bool|mixed
     * cette fonction retourne le dernier enrengistrement de la table message par rapport au categorie.
     */
    public function lastMessageCat($cat){

        return $this->db->prepare("SELECT * FROM `viewmsgcat` 
                                   WHERE codeCat=? 
                                   ORDER BY codeSujet DESC LIMIT 1",[$cat],null, true );
    }

    /**
     * @param $rep
     * @return array|bool|mixed
     * affiche tous les messages appartenant à un topic
     * @param $one bool c'est un booleen lorqu'il est à true la fonction affiche les donnees de la table
     * et lorsqu'il est à false on compte le nombre de colonne de la table
     */
    public function allMessage($rep, $one=false, $last= NULL){

        if ($last)
        {
            return $this->db->prepare("SELECT *, f_topics.datecreation AS dateactu  FROM f_topics 
                                       LEFT JOIN `viewmessage` 
                                       ON viewmessage.sjt=f_topics.idf_topics 
                                       LEFT JOIN users
                                       ON f_topics.id_createur=users.idusers
                                        WHERE f_topics.idf_topics=? ORDER BY msg DESC LIMIT 1 ", [$rep], null, true);
        }else{
            $req= $this->db->prepare("SELECT * FROM `viewmessage`  WHERE sjt=? LIMIT 0,30", [$rep]);

            if($one){
                return $req;
            }else{
                return count($req);
            }
        }


    }

    public function f_suivis($idusers, $idtopics){
        $req= $this->db->prepare("SELECT * FROM f_suivis WHERE id_users=? AND idf_topics=?", [$idusers, $idtopics]);
        return count($req);
    }
}