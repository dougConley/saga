<?php


class resist11
     {

         function review_resist11($bean, $event, $arguments)
         {
           // saco el id del registro que se está guardando
           $id_r11 = $bean->id;
           $test_null  = $bean->organo_1;
           $tipo_examen = $bean->tipo_estudio_ntl_1;
           
           if($tipo_examen=='Tomografía' and $test_null<>'') {
           

           // llevo los valores ingresados a un arreglo interno
           $arr_data = array(
                        "id" => $id_r11,
                        "rel_module" => $bean->rel_module,

                        "r11.organo_1" => $bean->organo_1,
                        "r11.organo_2" => $bean->organo_2,
                        "r11.organo_3" => $bean->organo_3,
                        "r11.organo_4" => $bean->organo_4,
                        "r11.organo_5" => $bean->organo_5,
                        "r11.tipo_lesion_1" => $bean->tipo_lesion_1,
                        "r11.tipo_lesion_2" => $bean->tipo_lesion_2,
                        "r11.tipo_lesion_3" => $bean->tipo_lesion_3,
                        "r11.tipo_lesion_4" => $bean->tipo_lesion_4,
                        "r11.tipo_lesion_5" => $bean->tipo_lesion_5,
                        "r11.medida_1" => $bean->medida_1,
                        "r11.medida_2" => $bean->medida_2,
                        "r11.medida_3" => $bean->medida_3,
                        "r11.medida_4" => $bean->medida_4,
                        "r11.medida_5" => $bean->medida_5,

                        "r11.tumor_markers"  => $bean->tumor_markers, // esta info no está en creación-rápida
                        "r11.tumor_markers_valor"  => $bean->valor_marcador_tumoral, // esta info no está en creación-rápida

                        "r11.n_lesiones_no_objetivo_1" => $bean->n_lesiones_no_objetivo,
                        "r11.n_lesiones_no_objetivo_2" => $bean->n_lesiones_no_objetivo_1,
                        "r11.n_lesiones_no_objetivo_3" => $bean->n_lesiones_no_objetivo_2,

                        "r11.tipo_ntl_1" => $bean->tipo_ntl_1,
                        "r11.tipo_ntl_2" => $bean->tipo_ntl_2,
                        "r11.tipo_ntl_3" => $bean->tipo_ntl_3,

                        "r11.fecha_estudio_ntl_1" => $bean->fecha_estudio_ntl1,
                        "r11.fecha_estudio_ntl_2" => $bean->fecha_estudio_ntl2,
                        "r11.fecha_estudio_ntl_3" => $bean->fecha_estudio_ntl3,

                        "r11.tipo_estudio_ntl_1" => $bean->tipo_estudio_ntl_1,
                        "r11.tipo_estudio_ntl_2" => $bean->tipo_estudio_ntl_2,
                        "r11.tipo_estudio_ntl_3" => $bean->tipo_estudio_ntl_3,

                        "r11.enlargement_of_ntl_1" => $bean->enlargement_of_ntl,
                        "r11.enlargement_of_ntl_2" => $bean->enlargement_of_ntl1,
                        "r11.enlargement_of_ntl_3" => $bean->enlargement_of_ntl2,

                        "r11.non_target_lesion_response" => $bean->non_target_lesion_response,
                        );

                   // módulo relacionado
                   // MIGRAR a modelo log__
                   //$tabla_interna = strtolower($bean->module_name);

                        // si es la 1ra vez que se ejecuta con este registro, saco fecha de creación como 'ahora'
                        // caso contrario viene en la data
                        if(isset(($arguments["related_module"]))) {
                                $tabla_interna = strtolower($arguments["related_module"]);
                        } else {
                                $tabla_interna = "";
                        }
                        if($tabla_interna <> '') {
                                        $arr_data["r11.date_entered"] = date('Y-m-d h:i:s');
                                        $arr_data["rel_module"] = $tabla_interna;
                                        $bean->rel_module = $tabla_interna;
                        } else {
                                        $arr_data["r11.date_entered"] = $bean->fetched_row['date_entered'];
                                        $tabla_interna = $arr_data["rel_module"];
                        }

                                // suma total de esta muestra
           $sum_total = $arr_data["r11.medida_1"] +
                                $arr_data["r11.medida_2"] +
                                $arr_data["r11.medida_3"] +
                                $arr_data["r11.medida_4"] +
                                $arr_data["r11.medida_5"];

                // la guardo en el arreglo
           $arr_data["suma_total"] = $sum_total;



                                // para obtener el id del modulo relacionado
           $sq = "select
                                {$tabla_interna}_r11_resist11_1{$tabla_interna}_ida as id_c
                        from
                                {$tabla_interna}_r11_resist11_1_c
                        where
                                {$tabla_interna}_r11_resist11_1r11_resist11_idb = '$id_r11' and
                                deleted = 0
                        limit 1";

           $res = $bean->db->query($sq);
           $row = $bean->db->fetchByAssoc($res);
           // id del registro relacionado
           $id_cto = $row['id_c'];
           $fecha_comparativa = $arr_data["r11.date_entered"];

                                // consulta de todas las muestras del registro antes identificado
           $sq1 = "select
                                r11.id,
                                r11.name as muestra,
                                date(r11.date_entered) as fecha,
                                r11.porcent_change,

                                r11.organo_1,
                                r11.organo_2,
                                r11.organo_3,
                                r11.organo_4,
                                r11.organo_5,
                                r11.tipo_lesion_1,
                                r11.tipo_lesion_2,
                                r11.tipo_lesion_3,
                                r11.tipo_lesion_4,
                                r11.tipo_lesion_5,
                                r11.medida_1,
                                r11.medida_2,
                                r11.medida_3,
                                r11.medida_4,
                                r11.medida_5,
                                r11.suma_total,

                                r11.tumor_markers,
                                r11.valor_marcador_tumoral as tumor_markers_valor,

                                r11.n_lesiones_no_objetivo as n_lesiones_no_objetivo_1,
                                r11.n_lesiones_no_objetivo_1 as n_lesiones_no_objetivo_2,
                                r11.n_lesiones_no_objetivo_2 as n_lesiones_no_objetivo_3,

                                r11.tipo_ntl_1 as tipo_ntl_1,
                                r11.tipo_ntl_2 as tipo_ntl_2,
                                r11.tipo_ntl_3 as tipo_ntl_3,

                                r11.fecha_estudio_ntl1 as fecha_estudio_ntl_1,
                                r11.fecha_estudio_ntl2 as fecha_estudio_ntl_2,
                                r11.fecha_estudio_ntl3 as fecha_estudio_ntl_3,

                                r11.tipo_estudio_ntl_1,
                                r11.tipo_estudio_ntl_2,
                                r11.tipo_estudio_ntl_3,

                                r11.enlargement_of_ntl as enlargement_of_ntl_l,
                                r11.enlargement_of_ntl1 as enlargement_of_ntl_2,
                                r11.enlargement_of_ntl2 as enlargement_of_ntl_3

                        from
                                {$tabla_interna}_r11_resist11_1_c rrc

                                inner join r11_resist11 r11 on
                                        r11.id = rrc.{$tabla_interna}_r11_resist11_1r11_resist11_idb and
                                        r11.deleted = 0
                        where
                                rrc.{$tabla_interna}_r11_resist11_1{$tabla_interna}_ida = '$id_cto' and
                                rrc.deleted = 0
                        order by
                                r11.date_entered";
           $res1 = $bean->db->query($sq1);

                                // variable varias para la lógica siguiente
           $suma_actual = $arr_data['suma_total'];
	       $ntl_tumor_markers = $arr_data["r11.tumor_markers"];
	       $ntl_tumor_markers_valor = $arr_data["r11.tumor_markers_valor"];
	       $ntl_actual_1 = $arr_data["r11.n_lesiones_no_objetivo_1"];
	       $ntl_actual_2 = $arr_data["r11.n_lesiones_no_objetivo_2"];
	       $ntl_actual_3 = $arr_data["r11.n_lesiones_no_objetivo_3"];
           $fecha_muestra_min = date('Y-m-d');
           $minimo = 9999999;
           $ntl_max = 0;
           $total_muestras = 1;
           $id_actual = $id_r11;
           $ntl_previas = false;
           $ntl_tm_val_max = 0;


           // búsqueda del Nadir, considerando todas las muestras
           // existentes, menos la propia que se está ingresando 
           
           while($muestra = $bean->db->fetchByAssoc($res1)) {
                if($muestra['id'] <> $arr_data["id"]) { // que no se vea a si misma si se vuelve a grabar

                           // 1ro, si hay lesiones Non Target, tengo una variable que lo indica
                      if(
                        ($muestra['n_lesiones_no_objetivo_1'] <> '' and $muestra['n_lesiones_no_objetivo_1'] > 0) or
                        ($muestra['n_lesiones_no_objetivo_2'] <> '' and $muestra['n_lesiones_no_objetivo_2'] > 0) or
                        ($muestra['n_lesiones_no_objetivo_3'] <> '' and $muestra['n_lesiones_no_objetivo_3'] > 0)
                      ) {
                        $ntl_actual = $muestra['n_lesiones_no_objetivo_1'] + $muestra['n_lesiones_no_objetivo_2'] + $muestra['n_lesiones_no_objetivo_3'];
                        $ntl_previas = true;
                    // Indicadores tumorales.
                      
                      	if($muestra['tumor_markers'] <> '' and $muestra['valor_marcador_tumoral'] > $ntl_tm_max ) {
                          $ntl_tm_val_max = $muestra['valor_marcador_tumoral'];
                        }
                      }


                    // $ntl_max es para almacenar el numero mayor de NTL a la fecha
                      if($ntl_actual > $ntl_max) {
                          $ntl_max = $ntl_actual;
                      }

                    // $minimo, $muestra_min y $fecha_muestra_min es para almacenar el registro con
                    // el nadir de las muestras
                    if( $muestra['suma_total'] <> '' and  $muestra['suma_total'] <= $minimo ) {
		                $minimo = $muestra['suma_total'];
		                $muestra_min = $muestra['muestra'];
		                $fecha_muestra_min = $muestra['fecha'];
                    }
                    // para llevar la cuenta de las muestras existentes
                    $total_muestras++;
                }
           }

           // Para controlar el nombre de las muestras con un correlativo
           // y la cola del ID del sistema (últ. sección separada por  '-' del id)
           $id_vector = explode("-",$id_cto);
           $id_tail = $id_vector[4]; // la cola del id
           $correlativo = $total_muestras - 1; // el correlativo
           if($total_muestras == 1) { // si hay sólo una muestra (o sea la actual), se trata del Base Line (BL))
		        $bean->name = "BL - $id_tail";
		        $muestra_min = "BL - $id_tail";
		        $minimo = $suma_actual;
           } else { // si no es la 1ra muestra selecciona la que antes se determinó por nafir
                $bean->name = "#".$correlativo ." - ". $id_tail;
           }

          // Para identificar la muestra menor, sin
          // considerar la actual
          if($minimo == $suma_actual and $total_muestras > 1) {
             $muestra_min = "#".$correlativo ." - ". $id_tail;
          }
              // cálculo variación actual (%)
              if($minimo > 0) {
	            $delta_actual = (($suma_actual / $minimo) - 1) * 100;
	            $delta_actual_html = number_format($delta_actual,2);
	           } else {
                $delta_actual = "0";
                $delta_actual_html = "0";
	           }
           // umbrales partial response y progresive desease
           $umbral_pr = -30;
           $umbral_pd = 20;

           // Glosa resultado TARGET LESIONS RESPONSE
           // $tlr es la variable onde almaceno el detalle.
           // $tlr_code es para determinar Overall response calculado más adelante
           if($total_muestras > 1) { // si tenemos más de una muestra ...
                   if($delta_actual <= $umbral_pr ) { // si el delta actual es menor que $umbral_pr de -30%
                                $tlr = "Partial Response (PR). $delta_actual_html % <= $umbral_pr %";
                                $tlr_code = "PR";
                   } elseif($delta_actual >= $umbral_pd ) { // si el delta actual es mayor que $umbral_pd de 20%
                                $tlr = "Progressive Disease (PD). $delta_actual_html % >= $umbral_pd %";
                                $tlr_code = "PD";
                   } else { // si no es ni PD ni PR, entonces es Stable Desease (SD)
                                $tlr = "Stable Desease (SD). $umbral_pr % > $delta_actual_html % < $umbral_pd % ";
                                $tlr_code = "SD";
                   }

                                        // si las muestras suman cero.... CORREGIR
                   if($minimo == 0) {
                                $tlr = "Target Lesion Response: Complete Resonde (CR). All target lesion gone.";
                                $tlr_code = "CR";
                   }

                } else { // si es la 1ra muestra
                                $tlr = "Muestra base (BL) con un valor de $minimo del $fecha_muestra_min ";
                }

           // Glosa resultado NO TARGET LESIONS RESPONSE

           $n_ntl = $arr_data["r11.n_lesiones_no_objetivo_1"] + $arr_data["r11.n_lesiones_no_objetivo_2"] + $arr_data["r11.n_lesiones_no_objetivo_3"];
           $enlarg_ntl = $arr_data["r11.enlargement_of_ntl_1"] + $arr_data["r11.enlargement_of_ntl_2"] + $arr_data["r11.enlargement_of_ntl_3"];

           if( $arr_data["r11.tumor_markers_valor"] = $ntl_tm_max ) {
                        $tumor_mark = "Elevated";
           } else {
                        $tumor_mark = "Normal_Level";
           }
           if($n_ntl == 0 ) { // si no hay TLR, se indica
                        $ntrl = "Non Target Lesions ommited";
                                        $ntlr_code = "OM";
           } elseif($enlarg_ntl >= '1' ) { // si el check box de Enlargement of NTL fue seleccionado
                        $ntrl = "Progressive Disease (PD). Enlargement of non-target lesions";
                                        $ntlr_code = "PD";

           } elseif($tumor_mark == "Elevated" and $ntl_actual >= 1) { // si no hay enlargement de las NTL, nos vamos al detalle de la cantidad y el indicador Tumor Markers
                        $ntrl = "Stable Disease (SD). Persistence of $ntl_actual>1 non-target lesion. Tumor marker level elevated";
                                        $ntlr_code = "SD";

           } elseif($n_ntl == '0' and $ntl_previas and $tumor_mark == "Normal_Level" ) { // si no hay NTL
                        $ntrl = "Completed Response (CR). All non-target lesions gone. Tumor markers to normal levels";
                                        $ntlr_code = "CR";

           } elseif($tumor_mark == "Normal_Level" and $ntl_actual >= 1) { // si sólo se indica que el Tumor Markers está en valores normales, sin NTL
                        $ntrl ="N/D";
                                        $ntlr_code = "N/D";
           } else { // si no es ninguna de las anteriores, situación estable
                        $ntrl = "Stable Disease (SD)";
                                        $ntlr_code = "SD";

           }

           // Overall Response Table
           $overall_response = "";
           if( $tlr_code == "CR" and $ntlr_code == "CR" and !$ntl_previas  ) { // si tlr = CR Y ntlr = CR Y no hay NTL previas => Complete Response (CR)
                        $overall_response = "Complete Response (CR)";
           } elseif( $tlr_code == "CR" and $ntlr_code == "SD" and !$ntl_previas ) { // si tlr = CR Y ntlr = SD Y no hat NTL previas => Partial Response (PR)
                        $overall_response = "Partial Response (PR)";
           } elseif( $tlr_code == "PR" and $ntlr_code <> "PD" and !$ntl_previas ) { // si tlr = PR Y ntlr = PD Y no hay NTL previas => Partial Response (PR)
                        $overall_response = "Partial response (PR)";
           } elseif( $tlr_code == "SD" and $ntlr_code <> "PR" and !$ntl_previas ) { // si tlr = SD Y ntlr = PR Y no hay NTL previas => Stable Desease (SD)
                        $overall_response = "Stable Desease (SD)";
           } elseif( $tlr_code == "PD" ) { // si tlr = Progresive Desease => Progresive Desease (PD)
                        $overall_response = "Progresive Desease (PD). Por PD en TLR";
           } elseif( $ntlr_code == "PD") { // si ntrl = Progresive Desease => Progresive Desease (PD)
                        $overall_response = "Progresive Desease (PD). Por PD en NTRL";
           } elseif( $ntl_previas ) { // si hay ntl previas => Progresive Desease (PD)
                        $overall_response = "Progresive Desease (PD) por NTL Previas";
           }
           // Glosa respecto de Target lesions
           $descrip = "Mínimo a la fecha: Muestra $muestra_min del $fecha_muestra_min
                                                con un valor de $minimo en un total de $total_muestras muestras.
                                                Delta de esta muestra es de $delta_actual_html %.
                                                Overall Response: $overall_response";

           // guardo en el registro los valores de suma total, porcentaje de cambio, desciption, targt lession response y non target lession response
           $bean->suma_total = $sum_total;
           $bean->porcent_change = $delta_actual;
           $bean->description = $descrip;
           $bean->target_lesion_response = $tlr;
           $bean->non_target_lesion_response = $ntrl;

           // FIN
        } else {

                   // módulo relacionado
                   // MIGRAR a modelo log__
                   //$tabla_interna = strtolower($bean->module_name);

                        // si es la 1ra vez que se ejecuta con este registro, saco fecha de creación como 'ahora'
                        // caso contrario viene en la data
                        if(isset(($arguments["related_module"]))) {
                                $tabla_interna = strtolower($arguments["related_module"]);
                        } else {
                                $tabla_interna = "";
                        }
                        if($tabla_interna <> '') {
                                        $bean->rel_module = $tabla_interna;
                        } 

                                // para obtener el id del modulo relacionado
         				  $sq = "select
                                {$tabla_interna}_r11_resist11_1{$tabla_interna}_ida as id_c
                        from
                                {$tabla_interna}_r11_resist11_1_c
                        where
                                {$tabla_interna}_r11_resist11_1r11_resist11_idb = '$id_r11' and
                                deleted = 0
                        limit 1";

           $res = $bean->db->query($sq);
           $row = $bean->db->fetchByAssoc($res);
           // id del registro relacionado
           $id_cto = $row['id_c'];


          	// datos del últimlo resist registrado
           $sq1 = "select
                                r11.id,
                                r11.name as muestra,
                                date(r11.date_entered) as fecha,
                                r11.porcent_change,

                                r11.organo_1,
                                r11.organo_2,
                                r11.organo_3,
                                r11.organo_4,
                                r11.organo_5,
                                r11.tipo_lesion_1,
                                r11.tipo_lesion_2,
                                r11.tipo_lesion_3,
                                r11.tipo_lesion_4,
                                r11.tipo_lesion_5,
                                r11.medida_1,
                                r11.medida_2,
                                r11.medida_3,
                                r11.medida_4,
                                r11.medida_5,
                                rrc.lesiones_target_1_c,
                                rrc.lesiones_target_2_c,
                                rrc.lesiones_target_3_c,
                                rrc.lesiones_target_4_c,
                                rrc.lesiones_target_5_c,
                                r11.num_linea_1,
                                r11.num_linea_2,
                                r11.num_linea_3,
                                r11.num_linea_4,
                                r11.num_linea_5,
                                r11.otro_tipo_de_lesion_1,
                                r11.otro_tipo_de_lesion_2,
                                r11.otro_tipo_de_lesion_3,
                                r11.otro_tipo_de_lesion_4,
                                r11.otro_tipo_de_lesion_5,
                                rrc.lesiones_no_target_1_c,
                                rrc.lesiones_no_target_2_c,
                                rrc.lesiones_no_target_3_c,
                                rrc.lesiones_no_target_4_c,
                                rrc.lesiones_no_target_5_c,
                                rrc.organo_nt_1_c,
                                rrc.organo_nt_2_c,
                                rrc.organo_nt_3_c,
                                rrc.organo_nt_4_c,
                                rrc.organo_nt_5_c,
                                rrc.lesion_preta_empeoram_1_c,
                                rrc.lesion_preta_empeoram_2_c,
                                rrc.lesion_preta_empeoram_3_c,
                                rrc.lesion_preta_empeoram_4_c,
                                rrc.lesion_preta_empeoram_5_c,
                                rrc.lesion_no_target_1_c,
                                rrc.lesion_no_target_2_c,
                                rrc.lesion_no_target_3_c,
                                rrc.lesion_no_target_4_c,
                                rrc.lesion_no_target_5_c,
                                r11.enlargement_of_ntl,
                                r11.enlargement_of_ntl1,
                                r11.enlargement_of_ntl2,
                                rrc.enlargement_of_ntl4_c,
                                rrc.enlargement_of_ntl5_c

                        from
                                {$tabla_interna}_r11_resist11_1_c rrc

                                inner join r11_resist11 r11 on
                                        r11.id = rrc.{$tabla_interna}_r11_resist11_1r11_resist11_idb and
                                        r11.deleted = 0
                        where
                                rrc.{$tabla_interna}_r11_resist11_1{$tabla_interna}_ida = '$id_cto' and 
                                rrc.id <> '{$bean->id}' and 
                                rrc.deleted = 0
                        order by
                                r11.date_entered desc  
                        limit 1 ";
           $res1 = $bean->db->query($sq1);
           
           // copio los datos del últ resist

          while($muestra = $bean->db->fetchByAssoc($res1)) {
          		$tmpname = explode('-'$muestra['muestra']);
					$bean->name = $tmpname[0].'-'.$tmpname[1].'-'date('DD MM yy');
					$bean->porcent_change = $muestra['porcent_change'];
					$bean->organo_1 = $muestra['organo_1'];
					$bean->organo_2 = $muestra['organo_2'];
					$bean->organo_3 = $muestra['organo_3'];
					$bean->organo_4 = $muestra['organo_4'];
					$bean->organo_5 = $muestra['organo_5'];
					$bean->tipo_lesion_1 = $muestra['tipo_lesion_1'];
					$bean->tipo_lesion_2 = $muestra['tipo_lesion_2'];
					$bean->tipo_lesion_3 = $muestra['tipo_lesion_3'];
					$bean->tipo_lesion_4 = $muestra['tipo_lesion_4'];
					$bean->tipo_lesion_5 = $muestra['tipo_lesion_5'];
					$bean->medida_1 = $muestra['medida_1'];
					$bean->medida_2 = $muestra['medida_2'];
					$bean->medida_3 = $muestra['medida_3'];
					$bean->medida_4 = $muestra['medida_4'];
					$bean->medida_5 = $muestra['medida_5'];
					$bean->lesiones_target_1_c = $muestra['lesiones_target_1_c'];
					$bean->lesiones_target_2_c = $muestra['lesiones_target_2_c'];
					$bean->lesiones_target_3_c = $muestra['lesiones_target_3_c'];
					$bean->lesiones_target_4_c = $muestra['lesiones_target_4_c'];
					$bean->lesiones_target_5_c = $muestra['lesiones_target_5_c'];
					$bean->num_linea_1 = $muestra['num_linea_1'];
					$bean->num_linea_2 = $muestra['num_linea_2'];
					$bean->num_linea_3 = $muestra['num_linea_3'];
					$bean->num_linea_4 = $muestra['num_linea_4'];
					$bean->num_linea_5 = $muestra['num_linea_5'];
					$bean->otro_tipo_de_lesion_1 = $muestra['otro_tipo_de_lesion_1'];
					$bean->otro_tipo_de_lesion_2 = $muestra['otro_tipo_de_lesion_2'];
					$bean->otro_tipo_de_lesion_3 = $muestra['otro_tipo_de_lesion_3'];
					$bean->otro_tipo_de_lesion_4 = $muestra['otro_tipo_de_lesion_4'];
					$bean->otro_tipo_de_lesion_5 = $muestra['otro_tipo_de_lesion_5'];
					$bean->lesiones_no_target_1_c = $muestra['lesiones_no_target_1_c'];
					$bean->lesiones_no_target_2_c = $muestra['lesiones_no_target_2_c'];
					$bean->lesiones_no_target_3_c = $muestra['lesiones_no_target_3_c'];
					$bean->lesiones_no_target_4_c = $muestra['lesiones_no_target_4_c'];
					$bean->lesiones_no_target_5_c = $muestra['lesiones_no_target_5_c'];
					$bean->organo_nt_1_c = $muestra['organo_nt_1_c'];
					$bean->organo_nt_2_c = $muestra['organo_nt_2_c'];
					$bean->organo_nt_3_c = $muestra['organo_nt_3_c'];
					$bean->organo_nt_4_c = $muestra['organo_nt_4_c'];
					$bean->organo_nt_5_c = $muestra['organo_nt_5_c'];
					$bean->lesion_preta_empeoram_1_c = $muestra['lesion_preta_empeoram_1_c'];
					$bean->lesion_preta_empeoram_2_c = $muestra['lesion_preta_empeoram_2_c'];
					$bean->lesion_preta_empeoram_3_c = $muestra['lesion_preta_empeoram_3_c'];
					$bean->lesion_preta_empeoram_4_c = $muestra['lesion_preta_empeoram_4_c'];
					$bean->lesion_preta_empeoram_5_c = $muestra['lesion_preta_empeoram_5_c'];
					$bean->lesion_no_target_1_c = $muestra['lesion_no_target_1_c'];
					$bean->lesion_no_target_2_c = $muestra['lesion_no_target_2_c'];
					$bean->lesion_no_target_3_c = $muestra['lesion_no_target_3_c'];
					$bean->lesion_no_target_4_c = $muestra['lesion_no_target_4_c'];
					$bean->lesion_no_target_5_c = $muestra['lesion_no_target_5_c'];
					$bean->enlargement_of_ntl = $muestra['enlargement_of_ntl'];
					$bean->enlargement_of_ntl1 = $muestra['enlargement_of_ntl1'];
					$bean->enlargement_of_ntl2 = $muestra['enlargement_of_ntl2'];
					$bean->enlargement_of_ntl4_c = $muestra['enlargement_of_ntl4_c'];
					$bean->enlargement_of_ntl5_c = $muestra['enlargement_of_ntl5_c'];
          }
	  $bean->save();
          
          // salto a vista del resist.
          $params = array(
                  'module'=> 'R11_Resist11',
                  'action'=>'EditView', 
                  'record' => $beanID
                );
        SugarApplication::redirect('index.php?' . http_build_query($params));

        
        }
       }
     }
?>


