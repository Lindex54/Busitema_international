-- Starter database seed data.
-- Default admin login: okelloericdenis@gmail.com / Busitema-d45d6a0d!
-- Change this password immediately after first login.

USE busitema_international;

INSERT INTO admin_users (name, email, password_hash, role)
VALUES (
    'Site Administrator',
    'okelloericdenis@gmail.com',
    '$2y$10$6izN4/pBOfmhquH4.kNPFe/0N1TfurTrQoodIp1hMHe6Etcb/iw4i',
    'admin'
)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO settings (`key`, `value`) VALUES
    ('office_address_line1', 'Jinja - Malaba Road'),
    ('office_address_line2', 'P.O. Box 236, Tororo, Uganda'),
    ('office_phone', '+256 454 448 808'),
    ('office_email', 'vc.adm@busitema.ac.ug'),
    ('office_hours', 'Mon - Fri, 8:00am - 5:00pm'),
    ('seo_site_name', 'Busitema University Internationalization & Partnerships'),
    ('seo_default_description', 'Busitema University''s Internationalization & Partnerships Office - international partnerships, scholarships, exchange opportunities and support for international students.'),
    ('seo_default_image', ''),
    ('seo_site_indexable', '1')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

INSERT INTO contact_people (name, role, bio, email, phone, photo, sort_order) VALUES
    (
        'Grace Nabirye',
        'International Relations Officer',
        'Guides incoming international students and partner institutions through admissions, orientation and day-to-day support.',
        'grace.nabirye@busitema.ac.ug',
        '+256 772 445 210',
        'grace-nabirye.svg',
        1
    ),
    (
        'David Okwii',
        'Partnerships & Exchange Coordinator',
        'Manages exchange agreements, staff mobility and new partnership enquiries from universities and organisations abroad.',
        'david.okwii@busitema.ac.ug',
        '+256 701 998 344',
        'david-okwii.svg',
        2
    )
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO partners (name, slug, region, country, about, logo, sort_order) VALUES
("Nile Valley Institute of Technology", "nile-valley-institute-of-technology", "Africa", "Kenya", "Nile Valley Institute of Technology is a leading science and engineering institution in East Africa, known for its applied research culture and modern laboratory facilities. The partnership with Busitema supports joint STEM research, shared use of specialised equipment, and structured exchange opportunities for engineering students and faculty.", NULL, 1),
("Sahel Development University", "sahel-development-university", "Africa", "Senegal", "Sahel Development University specialises in agricultural science and rural development, working closely with farming communities across West Africa. Through this partnership, researchers from both institutions collaborate on food security projects, climate-resilient farming techniques and joint field studies.", NULL, 2),
("Nordholm University", "nordholm-university", "Europe", "Sweden", "Nordholm University is recognised for its pioneering work in renewable energy and sustainable engineering. The partnership enables staff training exchanges, joint publications, and collaborative research into clean energy solutions suited to East African contexts.", NULL, 3),
("Rhine Valley University of Applied Sciences", "rhine-valley-university-of-applied-sciences", "Europe", "Germany", "Rhine Valley University of Applied Sciences offers hands-on, industry-aligned engineering education. Its exchange agreement with Busitema gives engineering students the chance to complete a semester abroad, gaining exposure to advanced manufacturing and applied research techniques.", NULL, 4),
("Highland University College", "highland-university-college", "Europe", "United Kingdom", "Highland University College is known for its community-centred approach to medical and public health education. This partnership focuses on public health training, joint community outreach, and knowledge exchange in disease prevention and primary care.", NULL, 5),
("Mekong Delta State University", "mekong-delta-state-university", "Asia", "Vietnam", "Mekong Delta State University conducts extensive research on river-delta ecosystems, water management and climate adaptation. Collaboration with Busitema centres on environmental science research and joint field studies examining shared challenges across tropical ecosystems.", NULL, 6),
("Pacific Rim Institute of Technology", "pacific-rim-institute-of-technology", "Asia", "Malaysia", "Pacific Rim Institute of Technology is a hub for information and communication technology innovation in Southeast Asia. The partnership supports student exchange in ICT programmes and joint technology showcases highlighting student-led innovation.", NULL, 7),
("Lakeview State University", "lakeview-state-university", "North America", "Canada", "Lakeview State University hosts a strong graduate research programme across the sciences and social sciences. Through this agreement, postgraduate students benefit from co-supervision arrangements and access to Lakeview\'s research infrastructure.", NULL, 8),
("Cascade Polytechnic University", "cascade-polytechnic-university", "North America", "United States", "Cascade Polytechnic University is known for its entrepreneurship and applied business programmes. The partnership connects Busitema students with internship and industrial attachment placements at partner organisations across the United States.", NULL, 9)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO partnership_areas (title, description, sort_order) VALUES
('Student Exchange', 'Semester and year-long exchange places for Busitema students abroad and incoming international students on our campuses.', 1),
('Staff & Faculty Mobility', 'Teaching visits, research sabbaticals and training placements that strengthen academic ties between institutions.', 2),
('Joint & Collaborative Research', 'Co-authored publications, shared laboratory access and joint applications for international research funding.', 3),
('Dual & Joint Degree Programmes', 'Structured study pathways that lead to recognised qualifications from Busitema and a partner institution.', 4),
('Capacity Building & Training', 'Short courses, workshops and professional development programmes for academic and administrative staff.', 5),
('Internships & Industrial Attachments', 'Practical placements with partner organisations, giving students hands-on, real-world experience.', 6),
('Community Engagement & Outreach', 'Joint community service, outreach clinics and development projects delivered alongside partner institutions.', 7),
('Grants & Resource Mobilisation', 'Collaborative proposals for research grants, infrastructure development and student support funding.', 8)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO opportunities (title, slug, category, status, deadline, summary, overview, eligibility, how_to_apply, sort_order) VALUES
("Busitema-Nordholm Graduate Exchange Scholarship", "busitema-nordholm-graduate-exchange-scholarship", "Scholarship", "Open", "31 October 2026", "Full tuition and a living stipend for one academic year of postgraduate study at Nordholm University, Sweden.", "This scholarship supports outstanding Busitema postgraduate students to spend one academic year at Nordholm University, studying alongside leading researchers in renewable energy and sustainable engineering. The award covers tuition, a monthly living stipend, and return airfare.", "Currently enrolled in a Busitema postgraduate programme
Minimum cumulative GPA of 4.0
Nomination from your faculty", "Collect a nomination letter from your Head of Department
Complete the online application form
Submit academic transcripts and a personal statement
Shortlisted candidates are interviewed by the International Relations Office", 1),
("Erasmus+ Staff Mobility Grant", "erasmus-staff-mobility-grant", "Staff Mobility", "Open", "15 November 2026", "Funding for teaching or training visits of up to two weeks at Rhine Valley University of Applied Sciences, Germany.", "The Erasmus+ Staff Mobility Grant funds short teaching and training visits for Busitema academic and administrative staff, supporting professional development and stronger institutional ties with Rhine Valley University of Applied Sciences.", "Full-time Busitema academic or administrative staff member
Support letter from your Dean or Director
A clear teaching or training plan for the visit", "Discuss your visit plan with your department
Complete the mobility grant application form
Attach your CV and a letter of support
Await confirmation from the International Relations Office", 2),
("Undergraduate Research Grant - Climate Resilience", "undergraduate-research-grant-climate-resilience", "Research Grant", "Upcoming", "Opens January 2027", "Seed funding for undergraduate-led climate research projects, delivered jointly with Mekong Delta State University.", "This grant provides seed funding for small undergraduate research teams investigating climate resilience and environmental adaptation, with mentorship and data-sharing support from Mekong Delta State University.", "Team of 2-4 registered undergraduate students
A faculty supervisor willing to mentor the project
A short project proposal (maximum 2 pages)", "Form a project team and identify a supervisor
Draft a short research proposal
Applications open in January 2027 - check back then", 3),
("Cascade Polytechnic Summer Internship Programme", "cascade-polytechnic-summer-internship-programme", "Internship", "Open", "28 February 2027", "An 8-week paid summer internship with partner organisations in the United States for business and entrepreneurship students.", "Selected students spend eight weeks with organisations connected to Cascade Polytechnic University, gaining hands-on experience in entrepreneurship, business operations and innovation. Housing support and a stipend are provided.", "Enrolled in a business, entrepreneurship or related programme
Completed at least two years of study
Conversational English proficiency", "Submit an online application with your CV
Complete a short video introduction
Attend an interview with the placement partner", 4),
("International Student Excellence Scholarship", "international-student-excellence-scholarship", "Scholarship", "Open", "Rolling admissions", "A partial tuition waiver for incoming international students admitted to any undergraduate programme.", "Busitema University offers a partial tuition waiver to academically strong international students joining any undergraduate programme, helping make a Busitema education more accessible to students from across the world.", "Admitted international student, any undergraduate programme
Strong academic record in prior studies
Complete admission requirements for international students", "Apply for admission through the Study section
Indicate interest in the scholarship on your application
Awards are confirmed alongside your admission offer", 5),
("Visiting Scholar Fellowship", "visiting-scholar-fellowship", "Fellowship", "Closed", "Closed for this cycle", "A short-term fellowship for international faculty to teach and conduct research at Busitema University.", "The Visiting Scholar Fellowship brings international faculty to Busitema for a term of teaching and collaborative research. This cycle is now closed; the next application window will be announced on this page.", "Faculty position at a partner or peer institution
A proposed teaching or research plan
Availability for a full academic term", "Applications are closed for this cycle
Contact our team to be notified when the next cycle opens", 6)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO campuses (name, district, focus, description, is_featured, is_new, sort_order) VALUES
("Busitema Campus", "Busia District", "Engineering & Technology", "The University’s main campus and administrative headquarters hosts the Faculty of Engineering and Technology. Its teaching and research focus on practical engineering, irrigation, agricultural mechanization, computing and innovation.", 1, 0, 1),
("Nagongera Campus", "Tororo District", "Science & Education", "Nagongera Campus hosts the Faculty of Science and Education. It prepares science educators and advances learning, research and innovation across science, mathematics, education and technology.", 0, 0, 2),
("Arapai Campus", "Soroti City", "Agriculture & Animal Sciences", "Arapai Campus hosts the Faculty of Agriculture and Animal Sciences. Its programmes and research support crop production, animal health and production, agribusiness, food security and sustainable agriculture.", 0, 0, 3),
("Namasagali Campus", "Kamuli District", "Natural Resources & Environment", "Namasagali Campus is dedicated to natural resources and environmental sciences. Its academic work covers environmental management, forestry, fisheries, water resources, climate resilience and conservation.", 0, 0, 4),
("Mbale Campus", "Mbale City", "Health Sciences", "Mbale Campus hosts the Faculty of Health Sciences. Students combine classroom learning with clinical training and community experience in medicine, nursing, public health and related health disciplines.", 0, 0, 5),
("Pallisa Campus", "Pallisa District", "Management Sciences", "Pallisa Campus is the home of the Faculty of Management Sciences. It develops professionals in business, entrepreneurship, accounting, finance, economics, procurement and public-sector management.", 0, 0, 6),
("Kabwangasi Campus", "Butebo District", "The newest campus", "Kabwangasi is Busitema University’s newest campus and an emerging academic hub in Butebo District. It is being developed to support health sciences and other strategic programmes aligned with national priorities.", 0, 1, 7)
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO news (title, slug, type, event_date, location, summary, body, image, published_at, is_featured, sort_order) VALUES
("Internationalization Office signs new memoranda with three partner universities", "internationalization-office-signs-new-memoranda-with-three-partner-universities", "News", NULL, NULL, "New agreements open fresh pathways for student exchange, joint research and staff mobility with three international institutions.", "The Internationalization Office has formalised new memoranda of understanding with three partner universities, expanding opportunities for student exchange, joint research and staff mobility.\n\nThe agreements were signed during a series of visits by partner delegations to Busitema Campus, and build on existing collaboration in engineering, agriculture and health sciences. Details of the first joint activities under these agreements will be shared on this page as they are confirmed.\n\nStudents and staff interested in exchange opportunities linked to these partnerships are encouraged to contact the Internationalization Office directly.", "hero/hero-partnerships.png", "2026-09-01", 1, 1),
("Study Abroad & Exchange Info Session", "study-abroad-exchange-info-session", "Event", "2026-09-25", "International Office, Busitema Campus", "Drop in to learn about current exchange placements, application timelines and how to prepare a competitive application.", "The Internationalization Office is hosting an open info session for students interested in study abroad and exchange opportunities.\n\nStaff will walk through currently available placements, eligibility requirements, application timelines and what to expect from the selection process. Past exchange students will also share their experiences and answer questions.\n\nNo registration is required - just come along with your questions.", "uganda/community-outreach.png", "2026-09-05", 0, 2),
("International students settle in during orientation week", "international-students-settle-in-during-orientation-week", "News", NULL, NULL, "New international students met campus ambassadors, toured Busitema Campus and got an introduction to academic and student life.", "This month's orientation programme welcomed a new group of international students to Busitema University. Over several days, students met campus ambassadors, toured academic departments and residence facilities, and were introduced to the support services available through the Internationalization Office.\n\nThe programme also covered practical guidance on registration, local transport and settling into life in Uganda. The Internationalization Office continues to support these students throughout their first semester.", "uganda/student-diversity.png", "2026-08-20", 0, 3),
("Cultural Exchange Day 2026", "cultural-exchange-day-2026", "Event", "2026-10-10", "Busitema Campus Grounds", "A campus-wide celebration of the cultures represented at Busitema, with food, performances and exhibitions from students and staff.", "Cultural Exchange Day brings together the many nationalities represented across Busitema University for a day of shared celebration. Expect food stalls, traditional performances, dress showcases and exhibitions organised by international and Ugandan students together.\n\nThe event is open to the whole university community. Groups interested in hosting a stall or performance should contact the Internationalization Office ahead of the event.", "uganda/culture.png", "2026-09-08", 0, 4),
("Grant secured for joint climate research with Mekong Delta State University", "grant-secured-for-joint-climate-research-with-mekong-delta-state-university", "News", NULL, NULL, "New funding will support joint fieldwork on climate-resilient agriculture between Busitema researchers and international partners.", "Busitema University researchers have secured joint funding to expand climate-resilient agriculture research in partnership with Mekong Delta State University.\n\nThe grant supports shared fieldwork, data collection and researcher exchange over the next two years, building on an existing collaboration between the two institutions. Findings are expected to directly inform farming practice recommendations for communities in Eastern Uganda.\n\nStudents interested in contributing to this research should reach out through the Internationalization Office.", "news/climate-smart-field-research.png", "2026-08-28", 0, 5),
("Scholarship Application Workshop", "scholarship-application-workshop", "Event", "2026-10-02", "Senate Conference Hall, Busitema Campus", "A hands-on session to help students put together strong scholarship applications ahead of upcoming deadlines.", "This workshop is designed to help students prepare strong applications for upcoming scholarship and exchange deadlines. Internationalization Office staff will cover how to write a compelling personal statement, gather the right supporting documents and avoid common application mistakes.\n\nPlaces are limited, so students are encouraged to arrive early. Bring a draft of your application materials if you have one, for feedback during the session.", "news/student-innovation-showcase.png", "2026-09-10", 0, 6)
ON DUPLICATE KEY UPDATE title = VALUES(title);
