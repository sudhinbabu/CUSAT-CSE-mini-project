SELECT 
    applications.user_id,
    marks.subject_name,
    applications.current_preference,
    applications.chellan_payment,
    applications.religion_id,
    (SUM(marks.awarded_mark) / sum(marks.max_mark) * 1200) AS index_mark
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id
WHERE applications.user_id NOT IN(SELECT 
    allotments.user_id
FROM
    allotments
        JOIN
    applications ON applications.user_id = allotments.user_id
WHERE
    applications.religion_id = 14 AND allotments.status ='confirmed')
GROUP BY applications.user_id
HAVING marks.subject_name = 'total_marks'
    AND applications.religion_id = 14
    AND applications.chellan_payment = 1
ORDER BY index_mark DESC
