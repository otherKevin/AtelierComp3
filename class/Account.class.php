<?php

class Account
{

    protected int $id; // Id
    protected string $owner; // Propriétaire
    protected float $balance; // Solde
    protected float $overdraft_limit; // Limite de découvert

    // ------------------------------- constructeur : --------------------------------------------------
    public function __construct(int $id, string $owner, string $balance, float $overdraft_limit)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->balance = $balance >= $overdraft_limit ? $balance : $overdraft_limit;
        $this->overdraft_limit = $overdraft_limit < 0 ? $overdraft_limit : 0;
    }

    // ---------------------------------- getters : -----------------------------------------------------

    public function getId(): string
    {
        return "BOCAL-BANK-" . $this->id;
    }
    public function getOwner(): string
    {
        return ucfirst(strtolower($this->owner));
    }
    public function getBalance(): string
    {
        return number_format($this->balance, 2) . " €";
    }
    public function getOverdraftLimit(): string
    {
        return number_format($this->overdraft_limit, 2) . " €";
    }

    // --------------------------------- setters ---------------------------------------------------------


    /* Réglage du solde */
    public function setBalance(float $newBalance)
    {
        if ($newBalance >= $this->overdraft_limit) {
            $this->balance = $newBalance;
        } else {
            echo "Opération refusée : le solde du compte ne doit pas être inférieur au découvert autorisé. Découvert autorisé : " . $this->overdraft_limit . " €.";
        }
    }

    /* Débit */
    public function debit(float $debit)
    {
        if ($this->balance - $debit >= $this->overdraft_limit) {
            $this->balance = $this->balance - $debit;
        } else {
            echo "Opération refusée : le solde du compte ne doit pas être inférieur au découvert autorisé. Découvert autorisé : " . $this->overdraft_limit . " €.";
            return false;
        }
    }
    /* Crédit (en version "idiotproof") */
    public function credit(float $credit)
    {
        if ($this->balance + $credit >= $this->overdraft_limit) {
            $this->balance = $this->balance + $credit;
        } else {
            echo "Opération refusée : le solde du compte ne doit pas être inférieur au découvert autorisé. Découvert autorisé : " . $this->overdraft_limit . " €.";
            return false;
        }
    }
}


// ----------------------------- Fonction d'affichage du client : ----------------------------------------
function displayCustomer($customer)
{
    if (isset($customer)) {
?>
        <p> ID du compte : <?= $customer->getId(); ?> </p>
        <p> Nom du titulaire : <?= $customer->getOwner(); ?> </p>
        <p> Solde du compte : <?= $customer->getBalance(); ?> </p>
        <p> Autorisation de découvert fixée à : <?= $customer->getOverdraftLimit(); ?> </p>
<?php }
}

// -------------------------------------- TEST EN DUR ------------------------------------------------------

// Création d'un client :
$Customer = new Account(215584, "marCeluS", 3658, -100);

// Affichage des données du client grâce à la fonction :
displayCustomer($Customer);


// Modification du solde à l'aide du setter :

$Customer->credit(150);

?>
<p> Solde après modification : <?= $Customer->getBalance(); ?> </p>



<?php
// -------------------------------------- Création d'une fonction de virement compte à compte -------------

/* Création de la fonction */

function virement(float $montant, Account $compte1, Account $compte2, bool $sensOperation)
{
    //récupération des valeurs
    $montant = $montant;
    $compte1 = $compte1;
    $compte2 = $compte2;
    $sensOperation = $sensOperation;

    if ($sensOperation) {
        $compte1->debit($montant);
        $compte2->credit($montant);
        echo "Virement effectué depuis le compte " . $compte1->getId() . " vers le compte " . $compte2->getId() . "<br/>";
    } else {
        $compte2->debit($montant);
        $compte1->credit($montant);
        echo "Virement effectué depuis le compte " . $compte2->getId() . " vers le compte " . $compte1->getId() . "<br/>";
    }


    echo "Nouveau solde du compte " . $compte1->getId() . " du client " . $compte1->getOwner() . " : " . $compte1->getBalance() . "<br/>";
    echo "Nouveau solde du compte " . $compte2->getId() . " du client " . $compte2->getOwner() . " : " . $compte2->getBalance() . "<br/>";
}


/* Création de deux comptes pour tester */
$Customer1 = new Account(125478, "Claudounet", 1000, -100);

$Customer2 = new Account(5689, "piteur", 1000, -100);

displayCustomer($Customer1);
displayCustomer($Customer2);

virement(1000, $Customer1, $Customer2, 0);
