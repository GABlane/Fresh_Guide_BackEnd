<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use RuntimeException;

class RoomDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = $this->descriptionsByCode();

        $blankCodes = [];
        foreach ($descriptions as $code => $description) {
            if (trim($description) === '') {
                $blankCodes[] = $code;
            }
        }
        if (!empty($blankCodes)) {
            throw new RuntimeException(
                'RoomDescriptionSeeder contains blank descriptions for codes: ' . implode(', ', $blankCodes)
            );
        }

        $existingCodes = Room::query()
            ->whereIn('code', array_keys($descriptions))
            ->pluck('code')
            ->all();

        $missingCodes = array_values(array_diff(array_keys($descriptions), $existingCodes));
        if (!empty($missingCodes)) {
            throw new RuntimeException(
                'RoomDescriptionSeeder could not find rooms for codes: ' . implode(', ', $missingCodes)
            );
        }

        foreach ($descriptions as $code => $description) {
            Room::query()
                ->where('code', $code)
                ->update(['description' => trim($description)]);
        }
    }

    /**
     * @return array<string, string>
     */
    private function descriptionsByCode(): array
    {
        return [
            'KITCHEN_LAB' => 'This laboratory is used for culinary and food preparation classes. Students perform recipe execution, kitchen workflow drills, and sanitation practice here. Follow posted safety and hygiene rules before entering.',
            'BARTENDER_LAB' => 'This lab supports beverage preparation and bar service training. Classes use it for mixology exercises, service simulation, and product familiarization. Equipment setup and cleanup are required after each session.',
            '108' => 'Lecture Room 108 is a standard classroom for regular academic subjects. It is commonly scheduled for discussion-based and lecture-based sessions. Check the class schedule outside the room before entry.',
            '105' => 'Lecture Room 105 is used for routine classroom instruction across multiple programs. It supports lectures, recitations, and small-group learning activities. Arrive early during peak hours to avoid hallway congestion.',
            '104' => 'Lecture Room 104 is a general-use teaching room for daytime class blocks. Faculty use this space for lectures, quizzes, and class consultations. Keep noise low in the corridor during ongoing classes.',
            'IT_CENTER' => 'The IT Center handles campus technology support and system concerns. Students and staff visit this office for account, device, and connectivity assistance. Bring valid school details when requesting technical help.',
            'GUIDANCE' => 'The Guidance Office provides counseling, student support, and referral services. You may visit for academic, personal, and wellness-related consultations. Walk-in availability depends on counselor schedule.',
            'PWD_CR' => 'This comfort room is designated for persons with disabilities. It is positioned for easier access from nearby hallways and offices. Keep the area clear and accessible at all times.',
            '109' => 'Lecture Room 109 is a larger classroom for higher-capacity sections. It is used for lectures, departmental briefings, and shared class activities. Seating layout may change depending on the assigned subject.',
            'FACULTY' => 'The Faculty Room is a shared workspace for instructors. Faculty hold preparation work, short consultations, and administrative coordination here. Students should knock and request entry politely when needed.',
            'HR_OFFICE' => 'The Human Resource Office manages personnel records and staff-related services. Official employment and administrative HR transactions are processed in this room. Prepare required documents before visiting.',
            'FINANCE' => 'Finance and Accounting handles payment-related and financial processing concerns. Students typically visit for fee verification and accounting endorsements. Queue times are longer near payment deadlines.',
            'PHOTO_LAB' => 'The Photography Laboratory is used for image production and media coursework. Students conduct camera exercises, lighting practice, and post-production sessions here. Handle all studio equipment with care.',
            'CRIMINOLOGY' => 'The Criminology Department office supports program coordination and student concerns. Advising, announcements, and course-related endorsements are facilitated here. Visit during office hours for official transactions.',
            '101' => 'Lecture Room 101 is a primary classroom near ground-floor access points. It is used for regular classes and introductory subject blocks. Verify section assignment before occupying the room.',
            'COURT' => 'The Campus Court is a multi-purpose space for sports, events, and assemblies. Departments may use this area for programs, practices, and student activities. Coordinate reservations through the proper office when required.',
            'REG' => 'The Registrar area assists with enrollment records, verification requests, and academic document processing. Students may coordinate forms and official school record concerns here. Bring valid identification and complete requirements before your visit.',
            'LIB' => 'The Library is a campus study and reference area for reading, research, and academic resource access. Students may use this space for quiet study and approved borrowing services. Follow library rules for silence, materials handling, and return schedules.',
            'ENT' => 'This is the main entrance point for students, staff, and visitors. It is the recommended starting location for wayfinding and route guidance. Follow campus security screening procedures upon entry.',
            'EXIT' => 'This is the primary exit route leading out of the campus. It is used for normal departure flow and guided evacuation routing when needed. Observe traffic and safety instructions while leaving.',

            'MAIN-2-LR211' => 'Lecture Room 211 is a standard second-floor classroom for scheduled subjects. It supports lecture delivery, seatwork, and classroom discussions. Check floor signage for section-specific instructions.',
            'MAIN-2-LR209' => 'Lecture Room 209 is used for regular academic classes on the second floor. Faculty conduct lectures, assessments, and consultation follow-ups in this room. Keep pathways clear during class transitions.',
            'MAIN-2-LR207' => 'Lecture Room 207 is assigned to routine instructional sessions across programs. It is commonly used for theory lessons and course discussions. Confirm your class time to avoid overlap with other sections.',
            'MAIN-2-LR205' => 'Lecture Room 205 serves as a general teaching room for second-floor schedules. It supports lecture, recitation, and short assessment activities. Enter quietly if a class is already in progress.',
            'MAIN-2-LR203' => 'Lecture Room 203 is a classroom for daily subject delivery and section meetings. It is frequently used during mid-morning and afternoon blocks. Follow posted classroom rules while inside.',
            'MAIN-2-LR201' => 'Lecture Room 201 is one of the main second-floor lecture spaces. Instructors use it for regular classes and supervised academic work. Refer to your timetable for exact room assignment by day.',
            'MAIN-2-LR212' => 'Lecture Room 212 is positioned on the right wing of the second floor. It handles regular lecture sessions and collaborative class activities. Keep doorways unobstructed during session changes.',
            'MAIN-2-LR210' => 'Lecture Room 210 is used for scheduled lectures and section-based instruction. It supports classroom discussions and written exercises. Verify section and subject code before entering.',
            'MAIN-2-LR208' => 'Lecture Room 208 is a general-purpose room for second-floor classes. Faculty use this room for lectures, quizzes, and consultation checkpoints. Follow room occupancy limits at all times.',
            'MAIN-2-LR206' => 'Lecture Room 206 supports regular instruction for multiple departments. It is suited for lecture-led and discussion-led class formats. Check nearby room labels when navigating this wing.',
            'MAIN-2-LR204' => 'Lecture Room 204 is a standard classroom used throughout the week. Classes here include lecture sessions and guided academic activities. Arrive before class start to minimize disruption.',
            'MAIN-2-LR202' => 'Lecture Room 202 is one of the core teaching rooms on the second floor. It is used for scheduled subjects and occasional makeup classes. Maintain classroom cleanliness after each session.',

            'MAIN-3-CBA-COORD' => 'The CBA Coordinators Office handles course coordination for business programs. Students may visit for advising, schedule clarifications, and program endorsements. Bring your section details when requesting assistance.',
            'MAIN-3-CBA-DEAN' => 'The CBA Dean Office supports administrative decisions and academic oversight for the college. Official concerns are received through proper appointment or office referral. Follow office protocol before entering.',
            'MAIN-3-LR310' => 'Lecture Room 310 is a third-floor classroom for regular course delivery. It is typically used for lecture and recitation sessions. Check schedule postings for room-sharing arrangements.',
            'MAIN-3-LR308' => 'Lecture Room 308 is used for academic instruction on the third floor. Classes include lecture blocks, discussions, and supervised coursework. Keep voice levels low in adjacent corridors.',
            'MAIN-3-CLAS-COORD' => 'This office supports CLAS program coordination and student academic concerns. Transactions include advising, documentation guidance, and schedule-related inquiries. Visit during posted office hours for faster processing.',
            'MAIN-3-SOUND-LAB' => 'The Sound Engineering Laboratory is used for audio production and sound system training. Students perform practical exercises on recording, mixing, and sound setup. Handle lab equipment only under instructor guidance.',
            'MAIN-3-LR304' => 'Lecture Room 304 is a general-use teaching room on the third floor. It accommodates lectures, recitations, and class-based assessments. Confirm the assigned section before occupying the room.',
            'MAIN-3-LR302' => 'Lecture Room 302 supports regular classroom sessions for different programs. Faculty use this room for lecture instruction and guided activities. Observe classroom etiquette and seating policies.',
            'MAIN-3-STUDENT-AFFAIRS' => 'The Office of Student Affairs and Services provides student support programs and welfare services. You can coordinate campus activities, concerns, and formal student requests here. Prepare complete details for quicker assistance.',
            'MAIN-3-MIS-DATA' => 'The MIS Data Center supports institutional IT systems and technical operations. Access is typically limited to authorized personnel and supervised activity. Coordinate with MIS staff before entering the area.',
            'MAIN-3-CS-DEPT' => 'The Computer Studies Department office manages academic concerns for computing programs. Students may visit for advising, forms, and departmental announcements. Verify office hours before proceeding.',
            'MAIN-3-MULTIMEDIA' => 'The Multimedia Room is used for digital media instruction and project work. Activities include editing, content production, and software-based coursework. Follow lab usage policies and workstation assignments.',
            'MAIN-3-LABTECH' => 'The Lab Tech Room is the support area for laboratory operations and maintenance. Technical preparation, troubleshooting, and resource handling are coordinated here. Report equipment concerns directly to staff.',
            'MAIN-3-COMPLAB1' => 'Computer Laboratory 1 is used for hands-on computing classes and software exercises. Students complete practical tasks, coding work, and guided lab activities here. Log in only to assigned stations and keep files organized.',
            'MAIN-3-COMPLAB2' => 'Computer Laboratory 2 supports practical computing courses and supervised lab sessions. Instructors use it for programming, productivity, and systems-related activities. Observe device handling and shutdown procedures after class.',
            'MAIN-3-COMPLAB3' => 'Computer Laboratory 3 is dedicated to lab-based coursework requiring workstation access. Sessions may include development tasks, technical drills, and application practice. Follow lab protocols for account use and equipment safety.',

            'MAIN-4-LR411' => 'Lecture Room 411 is a fourth-floor classroom for regular instruction. It is used for lectures, class discussions, and section-based assessments. Check your timetable for exact subject allocation.',
            'MAIN-4-EARLY-CHILD' => 'The Early Childhood Simulation Room supports practice teaching and learning simulations. Students perform instructional demonstrations and classroom management activities here. Setup materials after confirming faculty instructions.',
            'MAIN-4-LR408' => 'Lecture Room 408 is a general-use classroom for scheduled fourth-floor classes. It supports lecture and recitation formats across multiple subjects. Keep room furniture in its assigned layout after use.',
            'MAIN-4-PHYSICS-LAB' => 'The Physics Laboratory is used for experiments and applied science instruction. Classes conduct guided measurements, demonstrations, and laboratory exercises here. Wear required safety gear during practical sessions.',
            'MAIN-4-LR404' => 'Lecture Room 404 provides a standard classroom space for regular classes. Faculty use it for lectures, quizzes, and student reporting sessions. Arrive before class to avoid interrupting instruction.',
            'MAIN-4-LR402' => 'Lecture Room 402 is a fourth-floor classroom for theory and discussion classes. It is frequently assigned to routine academic schedules. Follow room policies on attendance and cleanliness.',
            'MAIN-4-COE-COUNCIL' => 'The COE Council Office serves student leadership and organizational coordination. Official council concerns, announcements, and program preparation are handled here. Coordinate with officers before using office resources.',
            'MAIN-4-EDTECH-LAB' => 'The Educational Technology Laboratory is used for technology-assisted teaching activities. Students and faculty run digital instruction, media tools, and lesson-tech integration exercises here. Use equipment according to posted lab guidelines.',
            'MAIN-4-BIO-LAB' => 'The Biology Laboratory supports life science practical work and demonstrations. Students perform supervised experiments and specimen-based activities in this room. Follow laboratory safety and disposal protocols strictly.',
            'MAIN-4-CHEM-LAB' => 'The Chemistry Laboratory is used for chemical experiments and analytical coursework. Practical sessions include reagent handling, observation, and lab reporting tasks. Safety procedures are mandatory while inside the lab.',
            'MAIN-4-LAW-OFFICE' => 'The College of Law Office handles law program administrative coordination. Students may process inquiries, notices, and official office requests here. Bring complete academic details for formal transactions.',
            'MAIN-4-LR401' => 'Lecture Room 401 is a standard fourth-floor lecture room for scheduled subjects. It is used for classes, discussions, and occasional consultation sessions. Verify class block assignments before entry.',
            'MAIN-4-COE' => 'The College of Education office supports academic administration for COE programs. Faculty and students process program concerns and documentation in this area. Respect office hours and queue procedures.',

            'MAIN-5-V48' => 'V-48 is a fifth-floor classroom used for regular academic instruction. Classes here may include lecture, reporting, and section activities. Confirm room assignment since nearby rooms have similar labels.',
            'MAIN-5-V46' => 'V-46 is a fifth-floor classroom for scheduled lecture sessions. It supports routine class discussions and seatwork activities. Keep noise low in the hallway during ongoing classes.',
            'MAIN-5-INDARTS' => 'The Industrial Arts Laboratory is used for hands-on technical and skills-based activities. Students perform guided practical work using workshop materials and tools. Follow instructor safety briefings before starting tasks.',
            'MAIN-5-SPEECH' => 'The Speech Laboratory supports communication training, speaking drills, and oral performance practice. Sessions may include recording, playback, and coached presentation activities. Maintain silence while another group is performing.',
            'MAIN-5-SEWING' => 'The Craft and Sewing Laboratory is used for textile and practical craft exercises. Students perform guided machine and handwork tasks during laboratory periods. Handle tools and materials according to lab safety rules.',
            'MAIN-5-OFFICE' => 'This fifth-floor office is used for administrative coordination on the right wing. Visitors may approach for room-related and floor-level office concerns. Check availability before initiating transactions.',
            'MAIN-5-AUDIT' => 'The Auditorium is the main venue for assemblies, programs, and large academic events. It is used for orientations, performances, and institutional gatherings. Coordinate event setup and access with authorized staff.',
        ];
    }
}
