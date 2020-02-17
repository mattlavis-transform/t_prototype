select * from duty_expression_descriptions ded order by 1;

select measurement_unit_qualifier_code from measurement_unit_qualifiers order by 1;
frand
select * from duty_expressions where duty_expression_id = '01';

select * from measurements m where measurement_unit_code = 'KGM' and measurement_unit_qualifier_code = 'N'

-- Get conditions for JSON
select mcc.condition_code as id, mccd.description as text from measure_condition_codes mcc, 
measure_condition_code_descriptions mccd
where mcc.validity_end_date is null
and mcc.condition_code = mccd.condition_code
order by mcc.condition_code;

-- Get conditions for options
select '<option value=''' || mcc.condition_code || '''>' || mcc.condition_code || ' - ' || mccd.description || '</option>' as text
from measure_condition_codes mcc, 
measure_condition_code_descriptions mccd
where mcc.validity_end_date is null
and mcc.condition_code = mccd.condition_code
order by mcc.condition_code;


select '"' || code || ' - ' || replace(description,'"','''') || '",' as text
from ml.ml_certificate_codes mcc
where validity_end_date is null
order by 1

select  '<option value=''' || mad.action_code || '''>' || mad.action_code || ' - ' || mad.description || '</option>' as text
from measure_action_descriptions mad
order by mad.action_code;

-- Get the possible combinations of condition code and action code
select condition_code, action_code, count(*)
from measure_conditions mc
group by condition_code, action_code
order by condition_code, action_code;

-- Get the measures where the condition code is A
select m.goods_nomenclature_item_id, m.geographical_area_id, m.measure_type_id,
mc.certificate_type_code, mc.certificate_code, mc.condition_duty_amount, condition_code, action_code, m.validity_start_date
from measure_conditions mc, ml.measures_real_end_dates m 
where m.measure_sid = mc.measure_sid
and (validity_end_date is null or validity_end_date > '2019-12-31')
--and condition_code = 'E'
--and certificate_type_code is not null
and action_code in ('08','28')
order by m.measure_sid, component_sequence_number


select distinct certificate_type_code, certificate_code
from measure_conditions mc, ml.measures_real_end_dates m 
where m.measure_sid = mc.measure_sid
and validity_end_date is null
and condition_code = 'A'
--and certificate_type_code is not null
and action_code = '28'
--and condition_duty_amount is not null
--and action_code = '14'
;

-- Get the measures where the condition code is A
select  condition_code, 
CASE
	WHEN mc.condition_duty_amount is null THEN 'Blank'
	ELSE 'Populated'
end as cheese, count(*)
from measure_conditions mc, ml.measures_real_end_dates m 
where m.measure_sid = mc.measure_sid
and validity_end_date is null
and condition_duty_amount is not null
group by mc.condition_code, mc.condition_duty_amount
order by mc.condition_code, mc.condition_duty_amount
;


select mc.action_code, mcc.duty_expression_id,
CASE
	WHEN mcc.duty_expression_id is null THEN 'Blank'
	ELSE 'Populated'
end as cheese
from measure_conditions mc left outer join measure_condition_components mcc 
on mc.measure_condition_sid = mcc.measure_condition_sid
group by  mc.action_code, mcc.duty_expression_id
order by  mc.action_code, mcc.duty_expression_id

select * from measurement_unit_descriptions mud order by 1;

select * from measure_components mc where measure_sid = 2051552;

	
select goods_nomenclature_item_id, additional_code_type_id || additional_code_id as additional_code,
measure_type_id, measure_generating_regulation_id as regulation, geographical_area_id as geographical_area,
'' as exclusions, validity_start_date, validity_end_date, '9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN' as duty
from measures
where additional_code_type_id is not null
and additional_code_type_id != 'V'
order by validity_start_date desc limit 100;

select footnote_type_id || footnote_id as id, description as text
from ml.ml_footnotes f
where validity_end_date is null
and footnote_type_id not in ('01', '02', '03')
order by footnote_type_id, footnote_id;


select '<option value="' || geographical_area_id, geographical_code, description from ml.ml_geographical_areas mga order by 1;


-- Geo area groups as option list
select '<option value="' || geographical_area_sid || '">' || geographical_area_id || ' ' || description || '</option>'
from ml.ml_geographical_areas mga where geographical_code = '1'
and validity_end_date is null
order by description;

-- <option value="2005">2005 GSP (R 12/978) - Annex IV</option>


select mga.geographical_area_id, mga.description, mga.geographical_area_sid, mga.geographical_code, 
mga.validity_start_date, mga.validity_end_date
from ml.ml_geo_memberships mgm, ml.ml_geographical_areas mga


select geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date, validity_end_date, parent_geographical_area_group_sid
from ml.ml_geographical_areas mga where geographical_area_sid = 400;
where mgm.child_sid = mga.geographical_area_sid
and mgm.parent_id = '1003'
order by 1, 2;


select additional_code_type_id, additional_code, validity_start_date, validity_end_date, code, description
from ml.ml_additional_codes ac
where additional_code_sid = 3766;


select validity_start_date, fd.description
from footnote_description_periods fdp, footnote_descriptions fd
where fd.footnote_type_id = 'MX' and fd.footnote_id = '027'
and fd.footnote_description_period_sid = fdp.footnote_description_period_sid
order by validity_start_date desc;


select footnote_type_id, footnote_id, count(*)
from footnote_descriptions
group by footnote_type_id, footnote_id
order by 3 desc 
limit 100;



select additional_code_type_id, additional_code, count(*)
from additional_code_descriptions
group by additional_code_type_id, additional_code
order by 3 desc 
limit 100;



select fd.description
from footnote_description_periods fdp, footnote_descriptions fd
where fd.footnote_type_id = 'MX' and fd.footnote_id = '027'
and fd.footnote_description_period_sid = fdp.footnote_description_period_sid
and fdp.validity_start_date = '2002-03-22'
order by validity_start_date desc;



select validity_start_date, acd.description
from additional_code_description_periods acdp, additional_code_descriptions acd
where acdp.additional_code_description_period_sid = acd.additional_code_description_period_sid
and acd.additional_code_sid = 100
order by validity_start_date desc;


select cdp.validity_start_date, cd.description
from certificate_description_periods cdp, certificate_descriptions cd
where cd.certificate_description_period_sid = cdp.certificate_description_period_sid
and cd.certificate_type_code = '9' and cd.certificate_code = 'CLM'
order by cdp.validity_start_date desc;

select description from certificate_description_periods cdp, certificate_descriptions cd
where cd.certificate_description_period_sid = cdp.certificate_description_period_sid
and cd.certificate_type_code = '9' and cd.certificate_code = 'CLM' and validity_start_date = '1971-12-31';


select gadp.validity_start_date, gad.description
from geographical_area_description_periods gadp, geographical_area_descriptions gad
where gad.geographical_area_description_period_sid = gadp.geographical_area_description_period_sid
and gad.geographical_area_sid = 400
order by gadp.validity_start_date desc;



select cdp.validity_start_date, cd.description
        from certificate_description_periods cdp, certificate_descriptions cd
        where cd.certificate_description_period_sid = cdp.certificate_description_period_sid
        and cd.certificate_type_code = $1 and cd.certificate_code = $2
        order by cdp.validity_start_date desc;
        
       
       select * from ml.ml_goods_nomenclatures mgn where goods_nomenclature_item_id = '0702000000';
      
      
select DISTINCT ON (gn.goods_nomenclature_sid)
gn.goods_nomenclature_item_id, gn.producline_suffix as productline_suffix, gn.statistical_indicator,
gn.validity_start_date, gn.validity_end_date,
gnd.description
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
where gndp.goods_nomenclature_description_period_sid = gnd.goods_nomenclature_description_period_sid
and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and gn.goods_nomenclature_sid = 50746
ORDER BY gn.goods_nomenclature_sid, gndp.validity_start_date desc
;


select gndp.goods_nomenclature_sid, count(*) from goods_nomenclature_description_periods gndp
group by gndp.goods_nomenclature_sid
order by 2 desc limit 100;
       ]

       
SELECT DISTINCT ON (gn.goods_nomenclature_item_id)
gn.goods_nomenclature_item_id, gn.goods_nomenclature_sid, description FROM chapters_sections cs,
goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
WHERE cs.goods_nomenclature_sid = gn.goods_nomenclature_sid
AND gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
AND gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
AND section_id = 1 ORDER BY gn.goods_nomenclature_item_id, gndp.validity_start_date desc;

       
       
select position, numeral, title from sections s order by 1;

       
select numeral, title from sections s where position = '1';


select * from chapters_sections cs;

select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, number_indents, description, chapter, node, leaf, significant_digits
from ml.goods_nomenclature_export_new('0101000000', '2019-01-01'); -- where number_indents = 0 and significant_digits = 4 order by 2, 3;

select * from ml.goods_nomenclature_export_new('01%', '2019-01-01') where number_indents = 0 and significant_digits <= 4 order by 2, 3;

select goods_nomenclature_item_id, producline_suffix, number_indents
from ml.goods_nomenclature_export_new('01%', '2019-01-01') order by 1, 2;

select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, number_indents, description, chapter, node, leaf, significant_digits
from ml.goods_nomenclature_export_new('0100000000', '2019-01-01');


select * from goods_nomenclature_indents gni where number_indents = 12;

insert into workbaskets (title, reason, user_id) values ('sdf', 'asd', 1);

select title, reason, type, status, user_id from workbaskets w where id = 15;

select uid, name, email from users where uid = 'matt.lavis';

select u.name as user_name, u.id as uid, u.uid as user_id, u.email as user_email, w.title, w.reason, w.type, w.status, w.updated_at, w.id count(*) OVER() AS full_count
from workbaskets w, users u where w.user_id = u.id limit 20 offset 0

select distinct on (gn.goods_nomenclature_item_id, gn.producline_suffix)
gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description, gn.goods_nomenclature_sid,
count(*) over() as full_count
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
where gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and description like '%halibut%'
order by gn.goods_nomenclature_item_id, gn.producline_suffix, gndp.validity_start_date desc;


select distinct on (gn.goods_nomenclature_item_id, gn.producline_suffix)
gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description, gn.goods_nomenclature_sid, gn.validity_start_date, gn.validity_end_date,
cs.section_id, count (gn.*) over() as full_count
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd,
goods_nomenclature_description_periods gndp, goods_nomenclatures gn2, chapters_sections cs
where gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and rpad(left(gn.goods_nomenclature_item_id, 2), 10, '0') = gn2.goods_nomenclature_item_id
and gn2.producline_suffix = '80' and cs.goods_nomenclature_sid = gn2.goods_nomenclature_sid 
and gn.validity_start_date < '2019-12-31' and (gn.validity_end_date >  '2019-12-31' or gn.validity_end_date is null)  AND ( lower(gnd.description) LIKE '%320190%'  OR  lower(gn.goods_nomenclature_item_id) LIKE '%320190%' )  limit 20 offset 0


select distinct on (gn.goods_nomenclature_item_id, gn.producline_suffix)
gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description,
gn.goods_nomenclature_sid, gn.validity_start_date, gn.validity_end_date, gni.number_indents
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd,
goods_nomenclature_description_periods gndp, goods_nomenclature_indents gni
where gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and gn.goods_nomenclature_sid = gni.goods_nomenclature_sid
and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and description like '%hake%'
order by gn.goods_nomenclature_item_id, gn.producline_suffix, gndp.validity_start_date desc;



select goods_nomenclature_item_id, producline_suffix, description, goods_nomenclature_sid,
validity_start_date, validity_end_date, number_indents, count (gn.*) over() as full_count
from ml.ml_commodity_codes gn
where description ilike '%hake%'
and (validity_end_date > '2012-12-31' or validity_end_date is null) 

select *, count (gn.*) over() as full_count
from ml.ml_commodity_codes gn where 'hake' in description;


select quota_order_number_id from quota_order_numbers qon


CREATE INDEX description_index ON goods_nomenclature_descriptions_oplog (description varchar_pattern_ops);





with og as (
	select quota_order_number_id, quota_order_number_sid
	from quota_order_numbers qon, quota_order_number_origins qono, ml.ml_geographical_areas ga
	where qon.quota_order_number_sid = qono.quota_order_number_sid
	and ga.geographical_area_sid = qono.geographical_area_sid

)

select m.ordernumber, m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id,
m.validity_start_date, m.validity_end_date, m.measure_generating_regulation_id,
ga.description as geographical_area_description, mtd.description as measure_type_description,
CASE
WHEN LEFT(m.ordernumber, 3) = '094' THEN 1
ELSE 0
END As licensed
from ml.measures_real_end_dates m, ml.ml_geographical_areas ga, measure_type_descriptions mtd
where m.measure_type_id in ('122', '123', '143', '146')
and m.geographical_area_sid = ga.geographical_area_sid;




with measure_types_current as (
  select *
  from measure_types
  where validity_end_date is null
)
select measures.*
from measures
inner join measure_types_current on measure_types_current.measure_type_id = measures.measure_type_id


select * from quota_order_numbers qon where quota_order_number_id like '098%'
update quota_order_numbers set quota_category = 'ATQ' where quota_order_number_id like '096%' or  quota_order_number_id like '097%'
update quota_order_numbers set quota_category = 'WTO' where quota_order_number_id like '093%' or  quota_order_number_id like '094%' or  quota_order_number_id like '095%'
update quota_order_numbers set quota_category = 'PRF' where quota_category is null;


select m.ordernumber as quota_order_number_id, m.measure_sid, m.measure_type_id, m.measure_type_id,
m.validity_start_date, m.validity_end_date, m.measure_generating_regulation_id as regulation_id,
ga.description as geographical_area_description, mtd.description as measure_type_description, qon.quota_category,
CASE
WHEN LEFT(m.ordernumber, 3) = '094' THEN 1
ELSE 0
END As licensed,
count (m.*) over() as full_count
from ml.ml_geographical_areas ga, measure_type_descriptions mtd, ml.measures_real_end_dates m
left outer join quota_order_numbers qon on qon.quota_order_number_id = m.ordernumber
where m.measure_type_id in ('122', '123', '143', '146')
and m.geographical_area_sid = ga.geographical_area_sid 
and m.measure_type_id = mtd.measure_type_id



select m.ordernumber as quota_order_number_id, m.measure_sid, m.measure_type_id, m.measure_type_id,
m.validity_start_date, m.validity_end_date, m.measure_generating_regulation_id as regulation_id, m.geographical_area_id
ga.description as geographical_area_description, mtd.description as measure_type_description, qon.quota_category,
CASE
WHEN LEFT(m.ordernumber, 3) = '094' THEN 1
ELSE 0
END As licensed,
count (m.*) over() as full_count
from ml.ml_geographical_areas ga, measure_type_descriptions mtd, ml.measures_real_end_dates m
left outer join quota_order_numbers qon on qon.quota_order_number_id = m.ordernumber
where m.measure_type_id in ('122', '123', '143', '146')
and m.geographical_area_sid = ga.geographical_area_sid 
and m.measure_type_id = mtd.measure_type_id


select * from quota_order_numbers qon

with temp as (
select distinct m.ordernumber as quota_order_number_id, m.measure_type_id, m.geographical_area_id,
ga.description as geographical_area_description, mtd.description as measure_type_description, qon.quota_category,
CASE
WHEN LEFT(m.ordernumber, 3) = '094' THEN 'Licensed'
ELSE 'FCFS'
END As mechanism
from ml.ml_geographical_areas ga, measure_type_descriptions mtd, ml.measures_real_end_dates m
left outer join quota_order_numbers qon on qon.quota_order_number_id = m.ordernumber
where m.measure_type_id in ('122', '123', '143', '146')
and m.geographical_area_sid = ga.geographical_area_sid 
and m.measure_type_id = mtd.measure_type_id
and qon.quota_category is not null
)
select temp.*, count(*) over() as full_count from temp;

select * from quota_order_numbers_oplog qono where quota_category is null;


update quota_order_numbers_oplog set quota_category = 'PRF' where quota_category is null;


select m.measure_sid, m.goods_nomenclature_item_id, m.validity_start_date, m.validity_end_date,
m.measure_type_id, ga.description as geographical_area_description, mtd.description as measure_type_description,
m.additional_code_type_id || m.additional_code_id as additional_code, m.measure_generating_regulation_id,
mc.duty_expression_id, mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code
from ml.ml_geographical_areas ga, measure_type_descriptions mtd, ml.measures_real_end_dates m
left outer join measure_components mc on m.measure_sid = mc.measure_sid	
where m.geographical_area_sid = ga.geographical_area_sid
and m.measure_type_id = mtd.measure_type_id
and m.measure_type_id = '710'
order by 1;

	

SELECT child_id as geographical_area_id, child_sid as geographical_area_sid, child_description as description, validity_start_date, validity_end_date
FROM ml.ml_geo_memberships WHERE parent_sid = 351
ORDER BY child_id;



select parent_id as geographical_area_id, parent_sid as geographical_area_sid, parent_description as description, validity_start_date, validity_end_date
from ml.ml_geo_memberships gm where child_sid = 253
order by parent_id;


select actmt.measure_type_id, description, validity_start_date, validity_end_date
from additional_code_type_measure_types actmt, measure_type_descriptions mtd
where actmt.measure_type_id = mtd.measure_type_id
and additional_code_type_id = 'C' order by 1;


select fagn.footnote_type, fagn.footnote_id, f.description, fagn.validity_start_date, fagn.validity_end_date
from footnote_association_goods_nomenclatures fagn, ml.ml_footnotes f
where fagn.footnote_type = f.footnote_type_id and fagn.footnote_id = f.footnote_id
and goods_nomenclature_sid = 94557
order by 1,2;

select * from footnote_types ft;

select ft.footnote_type_id, ftd.description, ft.validity_start_date,
        ft.validity_end_date, ft.application_code
        from footnote_types ft, footnote_type_descriptions ftd
        where ft.footnote_type_id = ftd.footnote_type_id
        and application_code not in ('3', '4', '8', '9') and validity_end_date is null order by 1


        
select * from footnote_association_measures fam where footnote_type_id = 'TP';


select * from footnotes where footnote_type_id = 'TP'


SELECT description, f.validity_start_date, f.validity_end_date, ft.application_code,
case
when ft.application_code in ('1', '2') then 'Nomenclature-related footnote'
when ft.application_code in ('6', '7') then 'Measure-related footnote'
else 'Not recommended'
end as application_code_description
FROM ml.ml_footnotes f, footnote_types ft
where f.footnote_type_id = ft.footnote_type_id
and f.footnote_id = '001' and f.footnote_type_id = 'CD'


-- Get measure-related footnotes
select m.measure_sid, m.measure_type_id, mtd.description as measure_type_description, m.geographical_area_id,
m.validity_start_date, m.validity_end_date, m.goods_nomenclature_item_id, m.goods_nomenclature_sid
from footnote_association_measures fam, measures m, measure_type_descriptions mtd 
where m.measure_sid = fam.measure_sid
and m.measure_type_id = mtd.measure_type_id
and m.validity_end_date is null
and footnote_id = '808' and footnote_type_id = 'CD' order by m.goods_nomenclature_item_id;

-- Get nomenclature-related footnotes
with association_cte as
        (
select distinct on (goods_nomenclature_sid)
fagn.goods_nomenclature_sid, fagn.validity_start_date, fagn.validity_end_date, gnd.description
from footnote_association_goods_nomenclatures fagn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
where fagn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
and footnote_type = 'TN' and footnote_id = '702'
order by goods_nomenclature_sid, gndp.validity_start_date
)
select *, count(*) over() as full_count from association_cte;
\





with footnote_types_cte as
(select ft.footnote_type_id, ftd.description, ft.validity_start_date,
ft.validity_end_date, ft.application_code,
case
when application_code in ('1', '2') then 'Nomenclature-related footnote'
when application_code in ('6', '7') then 'Measure-related footnote'
end as application_code_description
from footnote_types ft, footnote_type_descriptions ftd
where ft.footnote_type_id = ftd.footnote_type_id
and application_code not in ('3', '4', '5', '8', '9')
and ft.footnote_type_id not in ('01', '02', '03', 'MX') and validity_end_date is null order by 1)
select * from footnote_types_cte order by application_code_description, footnote_type_id


select max(footnote_id) from footnotes where footnote_type_id = 'CD' and footnote_id != '999';


select footnote_id from footnotes where footnote_type_id = 'CD' and footnote_id > '300' order by 1;


-- Successfully gets the first unused footnote in a single group - now how to do it in all groups
with footnote_type_cte as (
select footnote_id::int, ROW_NUMBER() OVER (ORDER BY footnote_id) rownumber from footnotes f
where footnote_type_id = 'CD')
select rownumber from footnote_type_cte where footnote_id != rownumber 
order by footnote_id limit 1;



with footnote_type_cte as (
select footnote_type_id, footnote_id::int, ROW_NUMBER() OVER (partition by footnote_type_id ORDER BY footnote_id) rownumber from footnotes f

)
select footnote_type_id,  lpad(min(rownumber)::text, 3, '0') as next_id from footnote_type_cte where footnote_id != rownumber
group by footnote_type_id order by footnote_type_id


SELECT s.i AS missing_cmd
FROM generate_series(1,999) s(i)
WHERE NOT EXISTS (SELECT 1 FROM footnotes WHERE footnote_id = s.i)

select lpad(generate_series(1, 999), 3, '0') as sfd

select generate_series(1, 999) as sfd

select * from footnotes where footnote_type_id  ='IS' order by 1;




SELECT s.i AS missing_cmd
FROM generate_series(1,999) s(i)
WHERE s.i NOT IN (SELECT footnote_id::int FROM footnotes where footnote_type_id = 'IS')
order by 1 asc limit 1;


SELECT rn tag                                                   
FROM (SELECT tag, ROW_NUMBER() OVER (ORDER BY tag) rn FROM footnotes) z 
WHERE rn != tag                                                                 
ORDER BY rn OFFSET 0 ROW FETCH NEXT 1 ROW ONLY;

-- Works for IS!!
select lpad(footnote_id::text, 3, '0')
from ( select generate_series (1, 999) as footnote_id
except select footnote_id::int from footnotes where footnote_type_id = 'MG') s
order by footnote_id limit 1;

select lpad(certificate_code::text, 3, '0') as next_id
from ( select generate_series (1, 999) as certificate_code
except select certificate_code::int from certificates where certificate_type_code = '9' and certificate_code < 'A') s
order by certificate_code limit 1;

select * from certificates where certificate_code = 'AID';

select '900' < 'A'

select max(additional_code) from ml.ml_additional_codes mac where additional_code_type_id not in ('V', 'X');


select distinct quota_order_number_id from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31';


-- SUCCESS = This gets the next available licensed quota order number
select lpad(ordernumber::text, 6, '0') as quota_order_number_id
from (
	select generate_series (94001, 94999) as ordernumber
	except select distinct ordernumber::int from measures where left(ordernumber, 3) = '094'
) subquery
order by ordernumber limit 1;

-- WTO

090, 091, 092, 093, 095




with cte as (
	select generate_series (90000, 90999) as quota_order_number_id
	union select generate_series (91000, 91999) as quota_order_number_id
	union select generate_series (92000, 92999) as quota_order_number_id
	union select generate_series (93000, 93999) as quota_order_number_id
	union select generate_series (94000, 94999) as quota_order_number_id
	order by 1
)
select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
from (
	select quota_order_number_id as quota_order_number_id from cte
	except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
) quota_order_number_id
order by quota_order_number_id limit 100;




SELECT * 
  FROM generate_series(30, 180, 30) "sequence";


-- Licensed: 094

-- FCFS WTO 090, 091, 092, 093, 095
-- FCFS ATQ 096, 097
-- FCFS PRF 098, 099
 
 
 
select m.measurement_unit_code, m.measurement_unit_qualifier_code, description
from measurements m, measurement_unit_qualifier_descriptions muqcd
where m.measurement_unit_qualifier_code = muqcd.measurement_unit_qualifier_code
and m.validity_end_date is null
order by m.measurement_unit_code, m.measurement_unit_qualifier_code;

select m.validity_start_date, mc.measurement_unit_code
from measure_components mc, measures m 
where mc.measure_sid = m.measure_sid
and mc.measurement_unit_code is not null;


-- get 10 random commodity codes
SELECT *
FROM goods_nomenclatures gn
WHERE validity_end_date is null
ORDER BY random()
LIMIT 10;

-- get 5 random additional codes
select additional_code_type_id || additional_code as additional_code
from additional_codes where validity_end_date is null
ORDER BY random()
LIMIT 5;

select * from goods_nomenclature_descriptions gnd
where description is not null
and description like '%<p%'
order by length(description) desc limit 500;ggq



select mts.measure_type_series_id, mtsd.description,
mts.validity_start_date, mts.validity_end_date, mts.measure_type_combination
from measure_type_series mts, measure_type_series_descriptions mtsd 
where mts.measure_type_series_id = mtsd.measure_type_series_id
and mts.validity_end_date is null
order by 1;

select * from goods_nomenclatures gn where goods_nomenclature_item_id = '0302540000';

with footnote_types_cte as (select ft.footnote_type_id, ftd.description, ft.validity_start_date, ft.validity_end_date, ft.application_code, case when application_code in ('1', '2') then 'Nomenclature-related footnote' when application_code in ('6', '7') then 'Measure-related footnote' end as application_code_description from footnote_types ft, footnote_type_descriptions ftd where ft.footnote_type_id = ftd.footnote_type_id and application_code not in ('3', '4', '5', '8', '9') and ft.footnote_type_id not in ('01', '02', '03', 'MX') and validity_end_date is null order by 1) select * from footnote_types_cte order by application_code_description, footnote_type_id 


select ('"' || f.footnote_type_id || footnote_id || ' - ' || left(regexp_replace(description, E'[\\n\\r"]+', ' ', 'g'), 120) || '",') as footnote
from ml.ml_footnotes f, footnote_types ft
where f.validity_end_date is null
and f.footnote_type_id = ft.footnote_type_id
and ft.application_code in ('1', '2')
and f.footnote_type_id not in ('01', '02', '03')
order by f.footnote_type_id, footnote_id;


select * from measures where measure_type_id in ('552', '554')

-- Fucking hell string_agg is good
select measure_type_series_id, string_agg(measure_type_id, ', ') as xxx from measure_types
group by 1;


select m.validity_start_date, m.measure_generating_regulation_id, mc.measure_sid, m.goods_nomenclature_item_id,
(m.additional_code_type_id || m.additional_code_id) as add_code, m.geographical_area_id
--string_agg(duty_expression_id::varchar || ' : ' || duty_amount::text, ', ') as xxx
from measure_components mc, ml.measures_real_end_dates m
where mc.measure_sid > 0
and m.measure_type_id in ('552', '554')
and m.measure_sid = mc.measure_sid
and m.additional_code_sid is not null
and m.validity_end_date is null
--and m.goods_nomenclature_item_id = '8714913035'
and m.geographical_area_id = 'CN'
--group by 1, 2, additional_code_type_id, additional_code_id, m.geographical_area_id
order by 1 desc, 2;

insert into ml.measure_prototype (workbasket_id) values (22);


select f.footnote_type_id, f.footnote_id, f.description
from ml.ml_footnotes f, ml.measure_prototype_footnotes mpf
where mpf.footnote_type_id = f.footnote_type_id and f.footnote_id = mpf.footnote_id
and mpf.measure_prototype_sid = 8
order by 1, 2;


select * from measure_type_descriptions mtd order by measure_type_id desc;

select component_sequence_number, condition_monetary_unit_code, condition_measurement_unit_code,
condition_measurement_unit_qualifier_code, action_code, certificate_type_code,	 certificate_code
from ml.measure_prototype_conditions mpc where measure_prototype_sid = 8
order by component_sequence_number;

select geographical_area_id, description
from ml.ml_geographical_areas ga
where validity_end_date is null
and geographical_code in ('10', '2')
order by description;

select roosm.validity_start_date, roosm.validity_end_date, roos.description
from ml.rules_of_origin_scheme_memberships roosm, ml.rules_of_origin_schemes roos
where roos.rules_of_origin_scheme_sid = roosm.rules_of_origin_scheme_sid
and geographical_area_id = 'IS' and geographical_area_sid = 53

select geographical_area_id, geographical_area_sid
from geographical_areas ga
where validity_end_date is null
and geographical_code != '1'
order by geographical_area_id, validity_start_date desc;

select rules_of_origin_scheme_sid, description, abbreviation, validity_start_date, validity_end_date
from ml.rules_of_origin_schemes roos order by 2



select * from ml.rules_of_origin_scheme_memberships roosm

select * from ml.rules_of_origin_scheme_memberships roosm


select rules_of_origin_scheme_sid, description, abbreviation
from ml.rules_of_origin_schemes roos order by 1

-- Get rules of origin applicability
select roosm.rules_of_origin_scheme_sid as scheme_id, roos.description as scheme_description,
        string_agg(mga.geographical_area_id || ' (' || mga.description || ')', ', ') as applicable_countries
from ml.rules_of_origin_scheme_memberships roosm, ml.rules_of_origin_schemes roos,
ml.ml_geographical_areas mga
where roos.rules_of_origin_scheme_sid = roosm.rules_of_origin_scheme_sid
and roosm.geographical_area_sid = mga.geographical_area_sid
and roosm.geographical_area_id != 'EH'
group by 1, 2
order by 1, 2, 3

delete from ml.roo_row;


select heading, description, processing_rule, chapter, country, "sequence"
from ml.roo_row where rules_of_origin_scheme_sid = 15



select * from ml.roo_row rr order by rules_of_origin_scheme_sid, chapter, "sequence";



select measure_type_series_id, string_agg(measure_type_id, ', ') as xxx from measure_types
group by 1;


select m.measure_type_id, description, count(m.*)
from ml.measures_real_end_dates m, measure_types mt, measure_type_descriptions mtd
where m.measure_type_id > '999' and m.validity_end_date is null
and mt.measure_type_series_id not in ('P', 'Q')
and m.measure_type_id = mt.measure_type_id
and mt.measure_type_id = mtd.measure_type_id
group by m.measure_type_id, mtd.description
order by 1;


select mc.condition_code, mccd.description, count(mc.*)
from measure_conditions mc, ml.measures_real_end_dates m, measure_condition_code_descriptions mccd
where mc.measure_sid = m.measure_sid
and m.validity_end_date is null
and mc.condition_code = mccd.condition_code
group by mc.condition_code, mccd.description
order by mc.condition_code

select * from certificate_descriptions cd where description like '%reland%'

select ma.action_code, mad.description from measure_actions ma, measure_action_descriptions mad
where ma.action_code = mad.action_code
and validity_end_date is null
order by ma.action_code, mad.description

select m.measure_type_id, m.goods_nomenclature_item_id, mc.*, m.*, m.validity_start_date, m.geographical_area_id
from measure_conditions mc, ml.measures_real_end_dates m
where condition_code in ('Z')
and m.validity_end_date is null
and m.measure_sid = mc.measure_sid
and m.measure_type_id = 'EQC'
--and condition_measurement_unit_code is not null and condition_measurement_unit_code != 'KGM'
-- and m.measure_sid = '3456963'
-- and m.measure_sid > 0
order by component_sequence_number desc,  m.validity_start_date desc
limit 100000;

select * from measure_conditions mc, measures m 
where condition_code = 'Z'
and m.measure_sid = mc.measure_sid
and m.validity_end_date is null
and mc.condition_duty_amount is not null
order by m.validity_start_date desc;

select distinct m.measure_type_id, mtd.description --, mc.certificate_type_code || mc.certificate_code as code
from measure_conditions mc, ml.measures_real_end_dates m, measure_type_descriptions mtd
where condition_code in ('Z')
and m.validity_end_date is null
and m.measure_sid = mc.measure_sid
and m.measure_type_id  = mtd.measure_type_id
--and condition_measurement_unit_code is not null and condition_measurement_unit_code != 'KGM'
--and m.measure_sid = '3571830'
limit 100000;



select distinct mc.certificate_type_code || mc.certificate_code as code, mcc.description
from measure_conditions mc, ml.measures_real_end_dates m, ml.ml_certificate_codes mcc
where condition_code in ('Y')
and mcc.certificate_code = mc.certificate_code and mcc.certificate_type_code = mc.certificate_type_code
and m.validity_end_date is null
and m.measure_sid = mc.measure_sid;


select * from measure_condition_code_descriptions mccd 
order by 1;

select mc.measure_sid, count(*)
from measure_conditions mc
group by measure_sid
order by 2 desc
limit 50

select * from measure_conditions mc where measure_sid = 3566626

select right(goods_nomenclature_item_id, 4), count(*)
from goods_nomenclatures gn where validity_end_date is null
and right(goods_nomenclature_item_id, 1) != '0'
group by 1 order by 2 desc;


select * from modification_regulations mr where mr.modification_regulation_id = 'R1110060';


select rg.regulation_group_id, rgd.description
from regulation_groups rg, regulation_group_descriptions rgd
where rg.regulation_group_id  = rgd.regulation_group_id
and validity_end_date is null
order by 1;


select act.additional_code_type_id, actd.description, act."national"
from additional_code_types act, additional_code_type_descriptions actd
where act.additional_code_type_id = actd.additional_code_type_id
and validity_end_date is null
order by 1

select * from additional_codes where additional_code > '999';

select validity_start_date, gnd.description, gn.goods_nomenclature_item_id
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd
where right(gn.goods_nomenclature_item_id, 2) = '00'
and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
--group by validity_start_date
order by validity_start_date desc
--limit 100;



select gnd.goods_nomenclature_item_id, validity_start_date, validity_end_date, gnd.description
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd
where validity_end_date >= '2019-01-01'
and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
order by validity_end_date desc limit 10000;



select validity_end_date, count (distinct goods_nomenclature_item_id)
from goods_nomenclatures gn
where validity_end_date >= '2019-01-01'
group by validity_end_date
order by validity_end_date asc
limit 10000;


select m.measure_type_id, gn.goods_nomenclature_item_id, gnd.description,
m.validity_start_date, gn.validity_start_date
from measures m, goods_nomenclatures gn, goods_nomenclature_descriptions gnd
where m.validity_start_date >= '2017-01-01'
and gn.validity_start_date >= '2017-01-01'
and m.validity_start_date = gn.validity_start_date
and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
and m.goods_nomenclature_sid = gn.goods_nomenclature_sid
order by m.validity_start_date desc;



select goods_nomenclature_item_id, validity_start_date, geographical_area_id,
case
	when right(goods_nomenclature_item_id, 8) = '00000000' then 2
	when right(goods_nomenclature_item_id, 6) = '000000' then 4
	when right(goods_nomenclature_item_id, 4) = '0000' then 6
	when right(goods_nomenclature_item_id, 2) = '00' then 8
	else 10
end
as significant_digits
from measures
where measure_type_id in ('142', '145')
and validity_start_date >= '2016-01-01'
and right(goods_nomenclature_item_id, 2) != '00'



select --goods_nomenclature_item_id, validity_start_date, geographical_area_id,
case
	when right(goods_nomenclature_item_id, 8) = '00000000' then 2
	when right(goods_nomenclature_item_id, 6) = '000000' then 4
	when right(goods_nomenclature_item_id, 4) = '0000' then 6
	when right(goods_nomenclature_item_id, 2) = '00' then 8
	else 10
end
as significant_digits, count(*)
from measures
where measure_type_id >= 'AAA'
--and measure_type_id >= 'AAA'
and validity_start_date >= '2010-01-01'
group by 1
order by 1;

select substr(goods_nomenclature_item_id, 6, 1), count(*)
from goods_nomenclatures gn
where validity_end_date is null
group by 1
order by goods_nomenclature_item_id, producline_suffix

select gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description
from goods_nomenclatures gn, goods_nomenclature_descriptions gnd
where substr(gn.goods_nomenclature_item_id, 6, 1) = '9'
and right (gn.goods_nomenclature_item_id, 4) = '0000'
and gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
order by goods_nomenclature_item_id, producline_suffix;


select *, description from goods_nomenclature_descriptions gnd where description like '%ite choc%'

select * from measures where goods_nomenclature_item_id = '1704903000' and measure_type_id = '103' order by validity_start_date desc;
select * from measure_components mc where measure_sid = 2051552 order by duty_expression_id;

select duty_expression_id, description
from duty_expression_descriptions ded
order by 1;

select distinct muqd.measurement_unit_qualifier_code, muqd.description
from measurement_unit_qualifier_descriptions muqd, measurement_unit_qualifiers muq,
measure_components mc, measures m
where muq.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
and mc.measurement_unit_qualifier_code = muq.measurement_unit_qualifier_code
and muq.validity_end_date is null
--and m.validity_start_date >= '2010-01-01'
and m.measure_sid = mc.measure_sid
order by muqd.measurement_unit_qualifier_code;

select m.measurement_unit_code, mud.description, m.measurement_unit_qualifier_code, muqd.description
from measurements m, measurement_unit_descriptions mud, measurement_unit_qualifier_descriptions muqd
where validity_end_date is null
and m.measurement_unit_code = mud.measurement_unit_code and m.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code
order by 1, 3;

	select * from measure_components mc where duty_expression_id = '99' and measurement_unit_qualifier_code is not null;

select * from geographical_areas ga where ga.parent_geographical_area_group_sid is not null;

select ga.geographical_area_id, count(*)
from geographical_areas ga 
group by geographical_area_id 
order by 2 desc;

select * from ml.ml_geographical_areas mga where geographical_code = '1' and description like '%and%';

select * from measures where geographical_area_id = '2300' order by validity_end_date  desc;

select mga.description, ga.*
from geographical_areas ga, ml.ml_geographical_areas mga
where ga.geographical_area_id  = mga.geographical_area_id 
and ga.validity_end_date  is not null order by ga.validity_end_date desc;

select * from ml.ml_footnotes mf ;

select f.footnote_type_id, ftd.description , count(f.*)
from footnotes f, footnote_type_descriptions ftd 
where f.footnote_type_id = ftd.footnote_type_id 
group by f.footnote_type_id, ftd.description 
order by footnote_type_id 

select * from footnote_type_descriptions ftd where footnote_type_id = 'TP'

select * from footnotes where c

select * from goods_nomenclature_descriptions gnd where description  like '%¬%';

select * from ml.ml_certificate_codes mcc where certificate_code > '999'


select distinct c.certificate_type_code, c.certificate_code -- , m.validity_start_date 
from ml.ml_certificate_codes c, measure_conditions mc, measures m, certificate_type_descriptions ctd 
where c.certificate_code = mc.certificate_code
and c.certificate_type_code = mc.certificate_type_code 
and m.measure_sid = mc.measure_sid 
and c.certificate_type_code = ctd.certificate_type_code 
and m.validity_start_date >= '2015-01-01'
and c.certificate_type_code = 'Y'
--order by 3,1

select * from measures m where ordernumber like '094%' order by validity_start_date desc

select * from certificates c where certificate_type_code = 'D' where 

select * from goods_nomenclature_descriptions gnd where description like '%hippoglossus%';

select distinct geographical_area_id
from ml.measures_real_end_dates mred
where  measure_type_id in ('x122', 'x123', '143', '146')
and (validity_end_date is null or validity_end_date > '2020-01-21');

select *
from ml.measures_real_end_dates mred
where  measure_type_id in ('x122', 'x123', '143', '146')
and geographical_area_id = 'GL'
and (validity_end_date is null or validity_end_date > '2020-01-21');

select * from additional_codes ac where additional_code > '999'

select * from measures where goods_nomenclature_item_id = '1905320500' and measure_type_id = '103' order by validity_start_date desc;

select * from measure_components mc where measure_sid = 2544964

select m.measure_sid , m.measure_type_id, m.goods_nomenclature_item_id,
m.validity_start_date, ac.additional_code_type_id, ac.additional_code, geographical_area_id
from additional_codes ac, ml.measures_real_end_dates m
where ac.validity_end_date is null
and m.validity_end_date is null 
and additional_code = '212'
and m.additional_code_sid = ac.additional_code_sid;

select * from measures where additional_code_type_id = 'C' and additional_code_id = '316'

select additional_code_type_id || additional_code as code, description
from additional_code_descriptions acd
order by additional_code_type_id, additional_code, oid desc;

select
actmt.additional_code_type_id, actd.description,
actmt.measure_type_id, mtd.description as measure_type_description
from additional_code_type_measure_types actmt, measure_type_descriptions mtd, additional_code_type_descriptions actd 
where mtd.measure_type_id = actmt.measure_type_id 
and actmt.validity_end_date is null
and actmt.additional_code_type_id = actd.additional_code_type_id 
and actmt.additional_code_type_id in (
'2',
'3',
'4',
'8',
'A',
'B',
'C'
) order by 1, 3;


WITH chapters AS (
    SELECT 
    left(goods_nomenclature_item_id, 2) as chapter,
        'Chapter ' || left(goods_nomenclature_item_id, 2) || ' - ' || description as chapter_description
         
    FROM
        goods_nomenclature_descriptions gnd 
    where right(goods_nomenclature_item_id, 8) = '00000000'
)
select m.ordernumber, m.goods_nomenclature_item_id, gnd.description, chapters.chapter_description
from ml.measures_real_end_dates m, goods_nomenclature_descriptions gnd, chapters
where m.ordernumber like '094%'
and m.goods_nomenclature_sid = gnd.goods_nomenclature_sid 
and m.validity_end_date is null
and left(m.goods_nomenclature_item_id, 2) = chapters.chapter
order by m.goods_nomenclature_item_id, m.ordernumber;

select quota_order_number_id, count(*)
from quota_order_numbers
where validity_start_date >= '2010-01-01'
group by quota_order_number_id
order by 2 desc;

select * from quota_order_numbers qon where quota_order_number_id = '098622'
order by validity_start_date 

select distinct validity_start_date from quota_definitions qd where validity_end_date - validity_start_date = 365


select * from quota_definitions where monetary_unit_code is not null and validity_end_date > '2020-01-01';

select distinct goods_nomenclature_item_id from ml.measures_real_end_dates m
where ordernumber is not null and (validity_end_date is null or validity_end_date > '2020-01-01');

select * from quota_order_numbers qon where lower(description) like '%aced%'

select qon.*
from quota_order_number_origins qono, quota_order_numbers qon 
where qono.quota_order_number_sid = qon.quota_order_number_sid 
and geographical_area_id = '2200';


with cte as (select c.certificate_type_code, c.certificate_code, c.code, c.description, c.validity_start_date, c.validity_end_date,
ctd.description as certificate_type_description,
case
    when ac.validity_end_date is not null then 'Terminated'
else 'Active'
end as active_state
from ml.ml_certificate_codes c, certificate_type_descriptions ctd
where c.certificate_type_code = ctd.certificate_type_code)
select *, count(*) OVER() AS full_count from cte where 1 > 0  limit 20 offset 0;





with cte as 
        (
            select br.base_regulation_id as base_regulation_id, validity_start_date, validity_end_date, effective_end_date,
            information_text, br.regulation_group_id, rgd.description as regulation_group_description,
            'Base' as regulation_type,
            case
                when (validity_end_date is null or effective_end_date is null) then 'Terminated'
            else 'Active'
            end as active_state
            from base_regulations br, regulation_group_descriptions rgd
            where br.regulation_group_id = rgd.regulation_group_id
            
            union 
            
            select mr.modification_regulation_id as base_regulation_id, mr.validity_start_date, mr.validity_end_date, mr.effective_end_date,
            mr.information_text, br.regulation_group_id as regulation_group_id, rgd.description as regulation_group_description,
            'Modification' as regulation_type, 
            case
                when (mr.validity_end_date is null or mr.effective_end_date is null) then 'Terminated'
            else 'Active'
            end as active_state
            from modification_regulations mr, base_regulations br, regulation_group_descriptions rgd
            where mr.base_regulation_id = br.base_regulation_id 
            and mr.base_regulation_role = br.base_regulation_role
            and br.regulation_group_id = rgd.regulation_group_id 
        )
        select *, count(*) OVER() AS full_count
        from cte where 1 > 0  AND ( lower(information_text) LIKE '%pig%'  OR  lower(base_regulation_id) LIKE '%pig%'  OR  lower(trade_remedies_case) LIKE '%pig%' )  AND date_part('year', validity_start_date) IN (2018) AND regulation_type IN ('Base')


with cte as (
	select *, 
	case
	    when m.validity_end_date is null then 'Terminated'
	else 'Active'
	    end as active_state
	from ml.measures_real_end_dates m
)        
select * from cte where 1 > 0
and goods_nomenclature_item_id = '0702000000'
limit 10


select measure_sid , goods_nomenclature_item_id , geographical_area_id , measure_type_id , measure_generating_regulation_id, ordernumber 
	case
	    when validity_end_date is null then 'Terminated'
	else 'Active'
	    end as active_state
from ml.measures_real_end_dates m
where 1 > 0
and goods_nomenclature_item_id = '0702000000';


select '"' || geographical_area_id || ' - ' || description || '",' as xxx
from ml.ml_geographical_areas mga where validity_end_date is null order by geographical_area_id ;



select '"' || footnote_type_id || footnote_id || ' - ' || REPLACE (regexp_replace(replace(replace(description, e'\t', ' '), '<br>', ' '), E'[\\n\\r]+', ' ', 'g'), '"', '') || '",' as xxx
from ml.ml_footnotes f where validity_end_date is null


with cte as (select c.certificate_type_code, c.certificate_code, c.code, c.description, c.validity_start_date, c.validity_end_date,
        ctd.description as certificate_type_description,
        case
            when c.validity_end_date is not null then 'Terminated'
            else 'Active'
	    end as active_state
        from ml.ml_certificate_codes c, certificate_type_descriptions ctd
        where c.certificate_type_code = ctd.certificate_type_code)
        select *, count(*) OVER() AS full_count from cte where 1 > 0  AND ( lower(description) LIKE '%europ%'  OR  lower(certificate_type_code||certificate_code) LIKE '%europ%' )  AND c.certificate_type_code IN ('9') AND active_state IN ('Active') limit 20 offset 0

        
select * from goods_nomenclature_descriptions where description like  '%Grain sorghum%';


select goods_nomenclature_item_id, producline_suffix, number_indents,
            description, leaf, significant_digits, validity_start_date, validity_end_date, node
            from ml.goods_nomenclature_export_new ('1007%', '2020-02-01')
            order by goods_nomenclature_item_id, producline_suffix;

           
select m.measure_sid, m.measure_type_id, m.goods_nomenclature_item_id, mc.duty_expression_id,
mc.duty_amount, mc.monetary_unit_code, mc.measurement_unit_code, mc.measurement_unit_qualifier_code,
m.validity_start_date, m.validity_end_date, mtd.description as measure_type_description, m.additional_code_id 
from measure_type_descriptions mtd, ml.measures_real_end_dates m
left outer join measure_components mc
on m.measure_sid = mc.measure_sid
where m.measure_type_id = mtd.measure_type_id
and m.additional_code_id not in ('x551', 'x552')
and m.validity_start_date <= '2020-02-01'
and (m.validity_end_date is null or m.validity_end_date >= '2020-02-01')
and m.measure_type_id in ('103', '105')  and left(m.goods_nomenclature_item_id, 4) = '1007'  order by m.goods_nomenclature_item_id, m.validity_start_date, mc.duty_expression_id;

select * from goods_nomenclatures gn
where goods_nomenclature_item_id like '0403%'
and validity_end_date is null
order by 1;

select mts.measure_type_series_id, mts.measure_type_combination, mtsd.description
from measure_type_series mts , measure_type_series_descriptions mtsd 
where mts.measure_type_series_id = mtsd.measure_type_series_id
order by 1;
order by 1

select mtd.measure_type_id, mtd.description, mt.*
from measure_types mt, measure_type_descriptions mtd 
where mt.measure_type_id = mtd.measure_type_id
and mt.measure_type_series_id = 'C'
and mt.validity_end_date is null
order by mt.measure_type_id ;

select mt.measure_type_series_id, mtsd.description, string_agg(measure_type_id, ', ' order by measure_type_id) as xxx
from measure_types mt, measure_type_series_descriptions mtsd 
where mt.measure_type_series_id = mtsd.measure_type_series_id 
and mt.validity_end_date is null
group by 1, 2
order by 1;


select distinct measure_type_id from ml.measures_real_end_dates mred
where mred.validity_end_date is null
order by 1;

select * from measures where measure_type_id = '690' order by validity_start_date desc

select * from measure_types where measure_type_id in ('122', '696');

select * from additional_code_type_measure_types actmt 
where actmt.validity_end_date is null

select * from ml.measures_real_end_dates mred
where measure_type_id = '696' and validity_end_date is null;

select * from measure_types where measure_type_id = '109'


select distinct measure_explosion_level from measure_types mt 

select goods_nomenclature_item_id, node, description from ml.commodity_friendly_names where node like '04%' order by 1

select * from goods_nomenclature_descriptions gnd where description like '%|mm%'

select roos.rules_of_origin_scheme_sid , roos.description, roosm.geographical_area_id, mga.description 
from ml.rules_of_origin_schemes roos, ml.rules_of_origin_scheme_memberships roosm,
ml.ml_geographical_areas mga 
where roos.rules_of_origin_scheme_sid = roosm.rules_of_origin_scheme_sid
and mga.geographical_area_id = roosm.geographical_area_id
order by 1, 4;


select left(measure_generating_regulation_id, 7), date_part('year', validity_start_date ), date_part('month', validity_start_date ), count(measure_sid)
from measures m
group by measure_generating_regulation_id, date_part('year', validity_start_date ), date_part('month', validity_start_date )
order by 4 desc, 1;


select * from ml.measures_real_end_dates m where (measure_type_id like '55%' or measure_type_id like '56%')
and (validity_end_date is null or validity_end_date > '2020-01-30');

select * from measures where measure_type_id = '464' and validity_end_date is null;

select * from measures m, measure_types mt
where m.measure_type_id = mt.measure_type_id 
and geographical_area_id = '1008'
--and measure_type_id = '122'
--and m.measure_type_id = '483'
and mt.trade_movement_code = 1
;

select * from geographical_area_memberships gam
where geographical_area_group_sid in (51, 217, 62)
and validity_end_date is not null
order by validity_end_date desc;


http://eur-lex.europa.eu/search.html?whOJ=NO_OJ%3DL114,YEAR_OJ%3D2019,PAGE_FIRST%3D0005&DB_COLL_OJ=oj-l&type=advanced&lang=en
http://eur-lex.europa.eu/search.html?whOJ=NO_OJ%3D44,YEAR_OJ%3D2018,PAGE_FIRST%3D0001&DB_COLL_OJ=oj-l&type=advanced&lang=en
http://eur-lex.europa.eu/search.html?whOJ=NO_OJ%3DL114,YEAR_OJ%3D2019,PAGE_FIRST%3D0005&DB_COLL_OJ=oj-l&type=advanced&lang=en

REGEXP_REPLACE (br.officialjournal_number, '[ D]', '')


select br.officialjournal_number, br.officialjournal_page, m.measure_generating_regulation_id,
'http://eur-lex.europa.eu/search.html?whOJ=NO_OJ%3D' || REGEXP_REPLACE (br.officialjournal_number, '[ D]', '') || ',YEAR_OJ%3D' || date_part('year', br.validity_start_date) || ',PAGE_FIRST%3D' || LPAD(br.officialjournal_page::text, 4, '0') || '&DB_COLL_OJ=oj-l&type=advanced&lang=en' as url,
geographical_area_id, goods_nomenclature_item_id, m.validity_start_date 
from measures m, modification_regulations br
where m.measure_generating_regulation_id = br.base_regulation_id
and measure_type_id = '695'
order by br.validity_start_date desc;

select * from measures where measure_generating_regulation_id  = 'R1801960';

select * from base_regulations br
order by operation_date desc
limit 10;

-- get the footnote and delete it
select * from footnotes f
order by operation_date desc
limit 10;

select * from footnote_descriptions fd 
order by operation_date desc
limit 10;

select * from footnote_description_periods fdp  
order by operation_date desc
limit 10;


-- get the additional code and delete it
select * from additional_codes f
order by operation_date desc
limit 10;

select * from additional_code_descriptions fd 
order by operation_date desc
limit 10;

select * from additional_code_description_periods fdp  
order by operation_date desc
limit 10;


-- get the geographical area and delete it
select * from geographical_areas ga
order by operation_date desc
limit 10;

select * from geographical_area_descriptions fd 
order by operation_date desc
limit 10;

select * from geographical_area_description_periods fdp  
order by operation_date desc
limit 10;


select right(goods_nomenclature_item_id, 1), count(*)
from goods_nomenclatures gn 
group by  right(goods_nomenclature_item_id, 1)
order by 2 desc


select m.measure_sid, m.goods_nomenclature_item_id, m.validity_start_date, mc.certificate_type_code, mc.certificate_code 
from ml.measures_real_end_dates m, measure_conditions mc 
where m.measure_type_id = '112' --and m.validity_end_date is null
and m.measure_sid = mc.measure_sid 
and mc.certificate_type_code is not null
order by validity_start_date desc, goods_nomenclature_item_id ;


select m.* 
from ml.measures_real_end_dates m, measure_conditions mc 
where m.measure_type_id = '119'
--and (m.validity_end_date is null or m.validity_end_date > '2020-02-01')
and m.measure_sid = mc.measure_sid 
and mc.certificate_type_code = 'C'
and mc.certificate_code = '119'
and mc.certificate_type_code is not null
order by validity_start_date desc; --goods_nomenclature_item_id;


select * from ml.measures_real_end_dates m -- , measure_conditions mc 
--where m.measure_sid = mc.measure_sid 
where m.validity_end_date is null 
and m.measure_type_id = '103'
and m.additional_code_type_id = '2'
and m.additional_code_id = '500';


select * from goods_nomenclature_descriptions gnd where lower(description) like '% note %'

Intended to be fitted in aircraft imported duty free or built within the Community

select  m.measure_type_id || m.additional_code_id as unique_code, mc.duty_amount
from measure_components mc, ml.measures_real_end_dates m
where additional_code_type_id = '7'
and mc.measure_sid = m.measure_sid 
and (validity_end_date is null or validity_end_date > '2020-02-01')
and geographical_area_id = 'JP'
and m.measure_type_id in ('672', '673', '674')
and reduction_indicator = 6
order by 1;

select m.goods_nomenclature_item_id, * from measures m, measure_components mc 
where geographical_area_id = 'JP'
and measure_type_id = '142' -- and validity_end_date is null
and m.measure_sid = mc.measure_sid 
and mc.duty_expression_id in ('12', '14');


select distinct m.goods_nomenclature_item_id
from measures m, goods_nomenclatures gn 
where measure_type_id = '488'
and m.goods_nomenclature_item_id = gn.goods_nomenclature_item_id 
and gn.producline_suffix = '80'
and gn.validity_end_date is null 
order by 1;

select *
from measures m
where measure_type_id = '490'
and m.validity_end_date > '2019-01-01'
and geographical_area_id = 'AU'
order by 1

select m.goods_nomenclature_item_id , m.geographical_area_id , mc.*
from ml.measures_real_end_dates m, measure_components mc 
where measure_type_id = '489'
and m.measure_sid = mc.measure_sid 
and (validity_end_date is null or validity_end_date > '2020-02-01')

select * from measure_types where measure_type_id like '65%' order by 1;

select * from measures where measure_type_id = '490' and validity_start_date >= '2019-01-01' -- unit price

select * from measure_components mc where measure_sid = 3740750; -- ADSZ

select * from monetary_unit_descriptions mud where monetary_unit_code = 'EUC'

select * from measures where additional_code_id = '507' and additional_code_type_id = '7' and geographical_area_id = '1011'
order by validity_start_date desc;

select * from ml.commodity_friendly_names cfn order by 1

select * from quota_order_numbers where quota_order_number_id = '098603';
select * from quota_order_number_origins qono where quota_order_number_sid = 3339;
select * from measures where ordernumber = '098603' and goods_nomenclature_item_id = '7209150000' order by validity_start_date desc;


select mc.* from measures m, measure_components mc
where measure_type_id in ('109', 'x110', 'x111')
and m.measure_sid = mc.measure_sid 
and mc.measurement_unit_qualifier_code is not null
and validity_end_date is null order by validity_start_date desc;

select mt.measure_type_series_id, m.measure_type_id, mc.*
from ml.measures_real_end_dates m, measure_conditions mc, measure_types mt
where m.measure_sid = mc.measure_sid 
and  m.measure_type_id = '724'
and m.measure_type_id = mt.measure_type_id 
and mt.measure_type_series_id in ('A', 'cB')
and m.validity_end_date is null;

select * from measures m where measure_type_id = '740';

select * from ml.measures_real_end_dates m where ordernumber like '094%' and validity_end_date > '2020-01-31'



SELECT mtd.description as description, validity_start_date, validity_end_date, trade_movement_code,
        priority_code, measure_component_applicable_code, origin_dest_code,
        order_number_capture_code, measure_explosion_level, mt.measure_type_series_id, mtsd.description as measure_type_series_description
        FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
        WHERE mt.measure_type_id = mtd.measure_type_id
        and mt.measure_type_series_id = mtsd.measure_type_series_id
        AND mt.measure_type_id = '305';
        
       
       
SELECT cd.description, validity_start_date, validity_end_date, ctd.description as certificate_type_description
FROM certificates c, certificate_descriptions cd, certificate_type_descriptions ctd
WHERE c.certificate_code = cd.certificate_code and c.certificate_type_code = ctd.certificate_type_code
AND c.certificate_type_code = 'Y' AND c.certificate_code = '902'


SELECT validity_start_date, regulation_group_id, information_text,
public_identifier, trade_remedies_case, url, validity_end_date
FROM base_regulations WHERE base_regulation_id = 'A0302360';
with association_cte as (
        select distinct on (goods_nomenclature_sid)
        fagn.goods_nomenclature_sid, fagn.validity_start_date, fagn.validity_end_date, gnd.goods_nomenclature_item_id, gnd.description
        from footnote_association_goods_nomenclatures fagn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
        where fagn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and footnote_type = 'TN' and footnote_id = '701'
        order by goods_nomenclature_sid, gndp.validity_start_date
        )  select *, count(*) over() as full_count from association_cte;
        
-- Workbasket - get footnote type items       
select wi.operation, act.additional_code_type_id, act.validity_start_date, act.validity_end_date, actd.description 
        from workbasket_items wi, additional_code_types_oplog act, additional_code_type_descriptions_oplog actd
        where wi.record_id = act.oid
        and act.additional_code_type_id = actd.additional_code_type_id 
        and wi.record_type = 'additional_code_type'
        and wi.workbasket_id = 33
        order by wi.created_at

        
        
with status_cte as (
            select f.footnote_type_id, f.footnote_id, f.description, f.validity_start_date, f.validity_end_date,
            ftd.description as footnote_type_description, ft.application_code,
            date_part('year', f.validity_start_date) as start_year,
            case
                when f.validity_end_date is not null then 'Terminated'
                else 'Active'
            end as active_state, f.status
            from ml.ml_footnotes f, footnote_type_descriptions ftd, footnote_types ft
            where f.footnote_type_id = ftd.footnote_type_id
            and f.footnote_type_id = ft.footnote_type_id
        )
        select *, count(*) OVER() AS full_count
        from status_cte f where 1 > 0  limit 20 offset 0;        

       
       
update modification_regulations_oplog set status = 'published' where status is null;





select wi.operation, act.additional_code_type_id, act.validity_start_date, act.validity_end_date, actd.description 
        from workbasket_items wi, additional_code_types_oplog act, additional_code_type_descriptions_oplog actd
        where wi.record_id = act.oid
        and act.additional_code_type_id = actd.additional_code_type_id 
        and wi.record_type = 'additional_code_type'
        and wi.workbasket_id = $1
        order by wi.created_at
        
        
        select wi.operation, ft.footnote_type_id, ft.validity_start_date, ft.validity_end_date, ftd.description --, wi.id
        from workbasket_items wi, footnote_types ft, footnote_type_descriptions ftd
        where wi.id = ft.oid
        and ft.footnote_type_id = ftd.footnote_type_id 
        and wi.record_type = 'footnote_type'
        and wi.workbasket_id = 33
        order by wi.created_at     
        
select record_id, record_type from workbasket_items wi where id = 43;

select wi.operation, ga.geographical_area_id, ga.validity_start_date, ga.validity_end_date,
ga.geographical_code || ' - ' || gc.description as geographical_code, gad.description,
wi.id, wi.record_id, '' as view_url
from workbasket_items wi, geographical_areas ga, geographical_area_descriptions gad, geographical_codes gc 
where wi.record_id = ga.oid
and wi.record_type = 'geographical_area'
and ga.geographical_code = gc.geographical_code 
and ga.geographical_area_sid = gad.geographical_area_sid 
and wi.workbasket_id = 33
order by wi.created_at
        
        


br.base_regulation_id, br.validity_start_date, br.information_text,
        br.url, br.public_identifier, br.trade_remedies_case,
        (br.regulation_group_id || ' - ' || rgd.description) as regulation_group_id, wi.id, wi.record_id,
        '' as view_url   
        
        
select * from measures where ordernumber = '090059' order by validity_start_date desc, goods_nomenclature_item_id 
select * from quota_order_numbers qon where quota_order_number_id = '090059'

select * from measures_oplog where goods_nomenclature_item_id = '2309109000' and ordernumber is not null


select additional_code_type_id || additional_code as additional_code from measure_activity_additional_codes
        where measure_activity_sid = 7
order by additional_code_type_id, additional_code




CREATE TABLE public.measure_activity_conditions (
	measure_condition_sid int4 NULL,
	condition_code varchar(255) NULL,
	component_sequence_number int4 NULL,
	condition_duty_amount float8 NULL,
	condition_monetary_unit_code varchar(255) NULL,
	condition_measurement_unit_code varchar(3) NULL,
	condition_measurement_unit_qualifier_code varchar(1) NULL,
	action_code varchar(255) NULL,
	certificate_type_code varchar(1) NULL,
	certificate_code varchar(3) NULL,
	measure_activity_sid int4 NULL
);

select measurement_unit_qualifier_code, upper(description) from measurement_unit_qualifier_descriptions muqd 
order by 1 desc

select distinct measurement_unit_code, measurement_unit_qualifier_code
from measure_components mc, measures m 
where m.measure_sid = mc.measure_sid 
order by 1, 2;

select * from duty_expression_descriptions ded order by duty_expression_id 

select measurement_unit_code, upper(description) from measurement_unit_descriptions mud
where measurement_unit_code not in ('KGM') order by 1 desc;

select measurement_unit_qualifier_code, upper(description) as description from measurement_unit_qualifier_descriptions mud
where measurement_unit_qualifier_code not in ('KGM') order by 1 desc;

select m.measure_sid, mcc.* from ml.measures_real_end_dates m, measure_conditions mc, measure_condition_components mcc 
where m.measure_sid = mc.measure_sid 
and mc.measure_condition_sid = mcc.measure_condition_sid 
and mc.condition_code = 'A'
and m.measure_type_id in ('552', '551', '553', '554')
and m.validity_end_date is null
order by m.measure_sid, mcc.duty_expression_id ;

select mc.measure_sid, mc.duty_expression_id, mc.duty_amount, mc.measurement_unit_code,
mc.measurement_unit_code, mc.monetary_unit_code
from measure_components mc 
where 1 > 0 and mc.measure_sid in (3348740);

select mcc.measure_condition_sid, mcc.duty_expression_id, mcc.duty_amount, mcc.measurement_unit_code,
mcc.measurement_unit_code, mcc.monetary_unit_code 
from measures m, measure_conditions mc
left outer join measure_condition_components mcc on mc.measure_condition_sid = mcc.measure_condition_sid 
where m.measure_sid = mc.measure_sid and m.measure_sid in (3348740);


select measure_sid, measure_generating_regulation_id, validity_start_date, validity_end_date,
goods_nomenclature_item_id, additional_code,
geographical_area_id, 'tbc' as exclusions, measure_type_id, measure_generating_regulation_id, ordernumber, status,
case
    when validity_end_date is null then 'Terminated'
	else 'Active'
end as active_state,
count(*) OVER() AS full_count
from ml.measures_real_end_dates m
where 1 > 0 and measure_sid in (3348740);
