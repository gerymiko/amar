<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->post("/submission/", function (Request $request, Response $response){

        $submission = $request->getParsedBody();

        $getktp = "SELECT COUNT(ktp) as total FROM tblreqloan WHERE ktp = ".$submission["ktp"]."";
        $ktp = $this->db->prepare($getktp);
        $ktp->execute();
        $rktp = $ktp->fetchObject();
        if($rktp->total > 0)
            return $response->withJson(["status" => "failed", "desc" => "duplicate ID"], 200);
        
        $birth = date("Y-m-d", strtotime($submission["tgl_lahir"]));
        $diff = date_diff(date_create($birth), date_create(date("Y-m-d")));
        $getbirth = intVal($diff->format('%y'));

        if($getbirth <= 17 || $getbirth >= 80)
            return $response->withJson(["status" => "failed", "desc" => "Age does not meet the standard"], 200);

        $getprov = "SELECT id FROM tblprovinsi WHERE id IN (1,2,3,4)";
        $prov = $this->db->prepare($getprov);
        $prov->execute();
        $rprov = $prov->fetchAll();

        if(array_search($submission['provinsi'], array_column($rprov, 'id')) == false) 
            return $response->withJson(["status" => "failed", "desc" => "Province does not meet the standard"], 200);

        $gethint = "SELECT datereq,hitreq FROM tblrequest WHERE datereq = '".date("Y-m-d")."'";
        $hint = $this->db->prepare($gethint);
        $hint->execute();
        $rhint = $hint->fetch();
        if (empty($rhint)) {
            $sql = "INSERT INTO tblrequest (datereq, hitreq) VALUE (:datereq, :hitreq)";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":datereq" => date("Y-m-d"),
                ":hitreq" => 1
            ];
            $stmt->execute($data);
        } elseif ($rhint['datereq'] == date("Y-m-d") && $rhint['hitreq'] < 50) {
            $sql = "UPDATE tblrequest SET hitreq=hitreq+1 WHERE datereq = '".date("Y-m-d")."'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } else {
            return $response->withJson(["status" => "failed", "desc" => "Requests exceed daily limit"], 200);
        }
        
        $getloan = "SELECT id,loan FROM tblloan WHERE id = ".$submission['jml_pinjaman']."";
        $loan = $this->db->prepare($getloan);
        $loan->execute();
        $rloan = $loan->fetchObject();

        $gettenor = "SELECT id,tenor FROM tbltenor WHERE id = ".$submission['jangka_waktu']."";
        $tenor = $this->db->prepare($gettenor);
        $tenor->execute();
        $rtenor = $tenor->fetchObject();

        $sql = "INSERT INTO tblreqloan (ktp, jml_pinjaman, jangka_waktu, tgl_lahir, email, provinsi ) VALUE (:ktp, :jml_pinjaman, :jangka_waktu, :tgl_lahir, :email, :provinsi)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":ktp" => $submission["ktp"],
            ":jml_pinjaman" => $submission["jml_pinjaman"],
            ":jangka_waktu" => $submission["jangka_waktu"],
            ":tgl_lahir" => date("Y-m-d", strtotime($submission["tgl_lahir"])),
            ":email" => $submission["email"],
            ":provinsi" => $submission["provinsi"]
        ];
        $stmt->execute($data);
        $id = $this->db->lastInsertId();

        $payment = (intval($rloan->loan) / intval($rtenor->tenor));

        for ($i=1; $i <= $rtenor->tenor; $i++) { 
            $sql = "INSERT INTO tblinstallment (cust_id, month, payment) VALUE (:cust_id, :month, :payment)";
            $stmt = $this->db->prepare($sql);
            $datainst = [
                ":cust_id" => $id,
                ":month" => $i,
                ":payment" => floatval($payment)
            ];
            $stmt->execute($datainst);
        }
    
        if($stmt->execute($datainst))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
};
