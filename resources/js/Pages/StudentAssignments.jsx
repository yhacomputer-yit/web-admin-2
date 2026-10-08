import { usePage } from "@inertiajs/react";
import { Head } from '@inertiajs/react';
import StudentLayout from "../Layouts/StudentLayout";

/* Assignments are an empty page on purpose.
 *
 * The assignment tables do not exist yet, so there is nothing real to show and
 * a list of invented rows would be worse than an empty page: a student would
 * read a due date that no teacher ever set. The shell stays -- the sidebar and
 * the back navigation are part of the portal, not part of the page -- but
 * nothing is rendered inside it until there is data to render.
 *
 * The controller passes no props for the same reason.
 */
export default function StudentAssignments() {
    const { url } = usePage();

    return (
        <StudentLayout active="assignments" title="Assignment" key={url}>
            <Head>
                <title>Assignments - Student Portal - YHA ACADEMY OF TECHNOLOGY</title>
                <meta name="description" content="View your assignments at YHA ACADEMY OF TECHNOLOGY Student Portal. Track due dates, submissions, and grades for all your courses." />
                <meta name="keywords" content="assignments, student portal, YHA ACADEMY OF TECHNOLOGY, homework, due dates, submissions, grades" />
                <meta property="og:title" content="Assignments - YHA ACADEMY OF TECHNOLOGY" />
                <meta property="og:description" content="View your assignments at YHA ACADEMY OF TECHNOLOGY Student Portal." />
                <meta property="og:image" content="/image/logo/logo.png" />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content="YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content="Assignments - YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:description" content="View your assignments at YHA ACADEMY OF TECHNOLOGY Student Portal." />
                <meta name="twitter:image" content="/image/logo/logo.png" />
                <link rel="canonical" href={window.location.href} />
            </Head>
        </StudentLayout>
    );
}