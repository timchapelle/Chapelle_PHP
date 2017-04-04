 /**
     * Exportation de tous les véhicules au format PDF (sous forme de tableau)
     */
    public function exportAllAsPDF() {
        $vehicules = $this->Vehicules->all();

        $pdf = $this->getPDF("Liste des véhicules", "Landscape");
        $html = "<style>th{background-color:#ececec;text-align:center}</style>";
        $html .= "<h1>Liste des véhicules</h1>";
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th width="50"><b>ID</b></th><th><b>Marque</b></th><th><b>Modèle</b></th><th><b>Plaque</b></th><th width="210"><b>Chassis</b></th><th><b>Type</b></th></tr>';
        foreach ($vehicules as $v) {
            $html .= '<tr><td width="50" align="center">' . $v->id . "</td><td>";
            $html .= $v->marque . "</td><td>";
            $html .= $v->modele . "</td><td>";
            $html .= $v->plaque . '</td><td width="210">';
            $html .= $v->numero_chassis . "</td><td>";
            $html .= $v->type . "</td></tr>";
        }
        $html .= "</table>";

        // Générer le contenu HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Remettre le pointeur sur la dernière page
        $pdf->lastPage();
        // Fermeture et affichage du document PDF
        $pdf->Output('Vehicules_Recapitulatif.pdf', 'I');
    }

    private function getPDF($title, $orientation) {

        $pdf = new \TCPDF_TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $creator = isset($_SESSION["login"]) ? $_SESSION["login"] : "Anonyme";

        $pdf->SetCreator($creator);
        $pdf->SetAuthor($creator);
        $pdf->SetTitle($title);
        $printedBy = "Garage Chapelle ";
        $printedBy .= "(Imprimé  par " . $creator . " le " . date('d/m/Y') . " à " . date('H:i') . ")";

        // Données du header
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, $printedBy);

        // Définition des polices du header et du footer
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Police 'monospaced' par défaut
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Définition des marges
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Sauts de page automatiques
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Mise à l'échelle des images
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Ajout d'une page
        $pdf->AddPage();

        return $pdf;
    }

    /**
     * Exportation d'un véhicule et ses réparations au format PDF
     */
    public function exportAsPDF() {

        $vehicule = $this->Vehicules->find($_GET["id"]);
        if ($vehicule) {
            $vehicule->reparations = $this->Reparations->findByVehiculeId($_GET["id"]);
            // Création d'un nouveau document PDF
            $pdf = $this->getPDF($vehicule->marque . ' ' . $vehicule->modele, 'landscape');

            // Définition du contenu du document
            $html = "<h1>" . $vehicule->marque . ' ' . $vehicule->modele . "</h1>";
            $html .= "<h3>Caractéristiques</h3>";
            $html .= "<ul>";
            $html .= "<li>Plaque : " . $vehicule->plaque . "</li>";
            $html .= "<li>N° de chassis : " . $vehicule->numero_chassis . "</li>";
            $html .= "</ul>";
            $html .= "<h3> Réparation";
            $html .= count($vehicule->reparations) > 0 ? "s" : "";
            $html .= "</h3>";
            // Si le véhicule a des réparations, on les liste
            if (!empty($vehicule->reparations)) {
                $cpt = 1;
                $html .= '<style>th{font-weight:bold;text-align:center;background-color:#ececec}</style>';
                $html .= '<table border="1" cellpadding="5">';
                $html .= '<tr><th width="40">Id</th><th>Intervention</th><th>Description</th><th>Date</th></tr>';
                foreach ($vehicule->reparations as $rep) {
                    $cpt++;
                    
                    $html .= "<tr>";
                    $html .= '<td width="40">'. $rep->id . "</td>";
                    $html .= "<td>". $rep->intervention . "</td>";
                    $html .= "<td>". $rep->description . "</td>";
                    $html .= "<td>". date('d/m/Y', strtotime($rep->date)) . "</td>";
                   
                    $html .= "</tr>";
                }
                $html .= "</table>";
            } else {
                // Sinon, on affiche un message
                $html .= "<h5>Pas encore de réparations pour ce véhicule</h5>";
            }
            /* Ajouter une page
              $pdf->AddPage();
              $html = '<h1>Hey</h1>';
             */
            // Générer le contenu HTML
            $pdf->writeHTML($html, true, false, true, false, '');

            // Remettre le pointeur sur la dernière page
            $pdf->lastPage();
            // Fermeture et affichage du document PDF
            $pdf->Output('Vehicule_' . $vehicule->id . '_Recapitulatif.pdf', 'I');
        } else {
            // Erreur 404 si un mauvais id est passé en paramètre
            $this->notFound();
        }
    }

    /**
     * Exportation des véhicules au format CSV
     */
    public function exportAsCSV() {

        $vehicules = $this->Vehicules->all();
        $file = fopen('test.csv', 'w');
        $output = fopen("php://output", "w");
        foreach ($vehicules as $v) {
            fputcsv($output, $v->toArray());
        }

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=test.csv");

        fclose($output);
    }

    /**
     * Exportation des réparations d'un véhicule au format CSV
     */
    public function exportReparationsAsCSV() {

        $id = $this->validateInt($_GET["id"]);
        if ($id) {
            $reparations = $this->Reparations->findByVehiculeId($id);
            $output = fopen("php://output", "w");
            foreach ($reparations as $r) {
                fputcsv($output, $r->toArray());
            }
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=vehicule_" . $id . "_reparations.csv");
            fclose($output);
        } else {
            $this->indexPHP("L'id indiqué n'est pas correct");
        }
    }

    /**
     * Importation d'un fichier CSV contenant des nouveaux véhicules
     */
    public function importCSV() {

        $file = $_FILES[0];

        $uploadDir = ROOT . '/assets/uploads/';
        $id = uniqid();

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $id . ".csv")) {
            $response = [
                "insertedRows" => 0,
                "errorRows" => 0,
                "errorRowNumbers" => ""
            ];

            $row = 0; // ligne actuelle

            if (($handle = fopen($uploadDir . $id . ".csv", "r"))) { // si on arrive à ouvrir le fichier en lecture
                while (($data = fgetcsv($handle, 1000, ","))) { // tant qu'on n'est pas à la fin du fichier
                    $num = count($data); // $num champs à la ligne $row
                    $row++;              // passage à la ligne suivante
                    for ($c = 0; $c < $num; $c++) {
                        // echo $data[$c] . "<br />\n";
                        $v = [];
                        $v["id"] = $data[0];
                        $v["marque"] = $data[1];
                        $v["modele"] = $data[2];
                        $v["plaque"] = $data[3];
                        $v["plaque1"] = $v["plaque"] != "" ? intval(substr($v["plaque"], 0, 1)) : 2;
                        $v["plaque2"] = $v["plaque"] != "" ? substr($v["plaque"], 2, 3) : "123";
                        $v["plaque3"] = $v["plaque"] != "" ? substr($v["plaque"], 6, 3) : "abc";
                        $v["numero_chassis"] = $data[4];
                        $v["type"] = $data[5];
                    }

                    $errors = $this->validateVehicule($v);

                    if (empty($errors)) {
                        $creation = $this->Vehicules->create([
                            "marque" => $v["marque"],
                            "modele" => $v["modele"],
                            "plaque" => $v["plaque"],
                            "numero_chassis" => $v["numero_chassis"],
                            "type" => $v["type"]
                        ]);
                    } else {
                        $response["errorRows"] ++;
                        $response["errorRowNumbers"] .= ($row . " ");
                    }
                    if ($creation) {
                        $response["insertedRows"] ++;
                    }
                }
                fclose($handle); // fermeture du fichier
                unlink($uploadDir . $id . ".csv"); // Destruction du fichier temporaire

                echo json_encode($response);
            }
        } else {
            $response = [
                "status" => "Fichier pas uploadé"
            ];
            echo json_encode($response);
        }
    }
