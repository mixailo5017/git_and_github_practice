<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_calc_member_pci_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION calc_member_pci(IN _member_id INT DEFAULT NULL)
          RETURNS INT AS $$
        DECLARE
          affected INT;
        BEGIN
          WITH base AS
          (
            SELECT uid FROM exp_members WHERE membertype = 5 AND (uid = _member_id OR _member_id IS NULL)
          ), general AS
          (
            SELECT m.uid,
              CASE WHEN COALESCE(userphoto, '') = ''     THEN 0 ELSE 1 END has_photo,
              CASE WHEN COALESCE(title, '') = ''         THEN 0 ELSE 1 END has_title,
              CASE WHEN COALESCE(organization, '') = ''  THEN 0 ELSE 1 END has_organization,
              CASE WHEN COALESCE(discipline, '') = ''    THEN 0 ELSE 1 END has_discipline,
              CASE WHEN COALESCE(country, '') = ''       THEN 0 ELSE 1 END has_country,
              CASE WHEN COALESCE(totalemployee, '') = '' THEN 0 ELSE 1 END has_totalemployee,
              CASE WHEN annualrevenue IS NULL            THEN 0 ELSE 1 END has_annualrevenue,
              CASE WHEN COALESCE(e.summary, '') = ''     THEN 0 ELSE 1 END has_summary,
              CASE WHEN COALESCE(e.areafocus, '') = ''   THEN 0 ELSE 1 END has_areafocus,
              CASE WHEN COALESCE(e.progoals, '') = ''    THEN 0 ELSE 1 END has_progoals,
              CASE WHEN COALESCE(e.success, '') = ''     THEN 0 ELSE 1 END has_success,
              CASE WHEN COALESCE(address, '') = ''       THEN 0 ELSE 1 END has_address,
              CASE WHEN COALESCE(city, '') = ''          THEN 0 ELSE 1 END has_city,
              CASE WHEN COALESCE(postal_code, '') = ''   THEN 0 ELSE 1 END has_postal_code,
              CASE WHEN COALESCE(public_status, '') = '' THEN 0 ELSE 1 END has_public_status
              FROM base b JOIN exp_members m
                ON b.uid = m.uid LEFT JOIN exp_expertise e
                ON m.uid = e.uid
          ), sectors AS
          (
            SELECT b.uid, CASE WHEN COUNT(s.id) > 0 THEN 1 ELSE 0 END has_sectors
              FROM base b LEFT JOIN exp_expertise_sector s
                ON b.uid = s.uid
             GROUP BY b.uid
          ), education AS
          (
            SELECT b.uid, CASE WHEN COUNT(e.educationid) > 0 THEN 1 ELSE 0 END has_education
              FROM base b LEFT JOIN exp_education e
                ON b.uid = e.uid
             GROUP BY b.uid
          ), pci_data AS
          (
            SELECT g.uid member_id,
                   10
                   + has_photo * 20
                   + has_discipline * 10
                   + has_country * 5
                   + has_sectors * 10
                   + (has_totalemployee & has_annualrevenue) * 10
                   + has_education * 5
                   + has_summary * 15
                   + has_title * 5
                   + (has_areafocus & has_progoals & has_success & has_address & has_city & has_postal_code & has_public_status) * 10 pci,
                   CURRENT_TIMESTAMP created_at
                   -- g.*, has_sectors, has_education
              FROM general g LEFT JOIN sectors s
                ON g.uid = s.uid LEFT JOIN education e
                ON g.uid = e.uid
          ), update_pci AS
          (
            UPDATE exp_member_pci p
               SET pci = d.pci
              FROM pci_data d
             WHERE p.member_id = d.member_id
             RETURNING d.member_id
          )
          INSERT INTO exp_member_pci (member_id, pci, created_at)
          SELECT member_id, pci, created_at
            FROM pci_data d
           WHERE NOT EXISTS (SELECT * FROM update_pci WHERE member_id = d.member_id);

          GET DIAGNOSTICS affected = ROW_COUNT;

          RETURN affected;
        END;
        $$ LANGUAGE plpgsql
        ";
        $this->db->query($sql);
        $this->db->query("SELECT calc_member_pci()");
    }

    public function down()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION calc_member_pci(IN _member_id INT DEFAULT NULL)
          RETURNS INT AS $$
        DECLARE
          affected INT;
        BEGIN
          WITH base AS
          (
            SELECT uid FROM exp_members WHERE membertype = 5 AND (uid = _member_id OR _member_id IS NULL)
          ), general AS
          (
            SELECT m.uid,
                   CASE WHEN COALESCE(userphoto, '') = '' THEN 0 ELSE 1 END has_photo,
                   CASE WHEN COALESCE(title, '') = '' THEN 0 ELSE 1 END has_title,
                   CASE WHEN COALESCE(organization, '') = '' THEN 0 ELSE 1 END has_organization,
                   CASE WHEN COALESCE(discipline, '') = '' THEN 0 ELSE 1 END has_discipline,
                   CASE WHEN COALESCE(country, '') = '' THEN 0 ELSE 1 END has_country,
                   CASE WHEN COALESCE(totalemployee, '') = '' THEN 0 ELSE 1 END has_totalemployee,
                   CASE WHEN COALESCE(annualrevenue, 0) = 0 THEN 0 ELSE 1 END has_annualrevenue,
                   CASE WHEN COALESCE(e.summary, '') = '' THEN 0 ELSE 1 END has_summary,
                   CASE WHEN COALESCE(e.areafocus, '') = '' THEN 0 ELSE 1 END has_areafocus,
                   CASE WHEN COALESCE(e.progoals, '') = '' THEN 0 ELSE 1 END has_progoals,
                   CASE WHEN COALESCE(e.success, '') = '' THEN 0 ELSE 1 END has_success,
                   CASE WHEN COALESCE(address, '') = '' THEN 0 ELSE 1 END has_address,
                   CASE WHEN COALESCE(city, '') = '' THEN 0 ELSE 1 END has_city,
                   CASE WHEN COALESCE(postal_code, '') = '' THEN 0 ELSE 1 END has_postal_code,
                   CASE WHEN COALESCE(public_status, '') = '' THEN 0 ELSE 1 END has_public_status
              FROM base b JOIN exp_members m
                ON b.uid = m.uid LEFT JOIN exp_expertise e
                ON m.uid = e.uid
          ), sectors AS
          (
            SELECT b.uid, CASE WHEN COUNT(s.id) > 0 THEN 1 ELSE 0 END has_sectors
              FROM base b LEFT JOIN exp_expertise_sector s
                ON b.uid = s.uid
             GROUP BY b.uid
          ), education AS
          (
            SELECT b.uid, CASE WHEN COUNT(e.educationid) > 0 THEN 1 ELSE 0 END has_education
              FROM base b LEFT JOIN exp_education e
                ON b.uid = e.uid
             GROUP BY b.uid
          ), pci_data AS
          (
            SELECT g.uid member_id,
                   10
                   + has_photo * 20
                   + has_discipline * 10
                   + has_country * 5
                   + has_sectors * 10
                   + (has_totalemployee & has_annualrevenue) * 10
                   + has_education * 5
                   + has_summary * 15
                   + has_title * 5
                   + (has_areafocus & has_progoals & has_success & has_address & has_city & has_postal_code & has_public_status) * 10 pci,
                   CURRENT_TIMESTAMP created_at
                   -- g.*, has_sectors, has_education
              FROM general g LEFT JOIN sectors s
                ON g.uid = s.uid LEFT JOIN education e
                ON g.uid = e.uid
          ), update_pci AS
          (
            UPDATE exp_member_pci p
               SET pci = d.pci
              FROM pci_data d
             WHERE p.member_id = d.member_id
             RETURNING d.member_id
          )
          INSERT INTO exp_member_pci (member_id, pci, created_at)
          SELECT member_id, pci, created_at
            FROM pci_data d
           WHERE NOT EXISTS (SELECT * FROM update_pci WHERE member_id = d.member_id);

          GET DIAGNOSTICS affected = ROW_COUNT;

          RETURN affected;
        END;
        $$ LANGUAGE plpgsql
        ";
        $this->db->query($sql);
        $this->db->query("SELECT calc_member_pci()");
    }
}