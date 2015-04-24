SELECT 
DISTINCT religions.religion_id, religions.reservation_percentage
FROM
    religions
JOIN applications ON applications.religion_id = religions.religion_id
WHERE
    reservation_percentage > 0

ORDER BY reservation_percentage DESC
