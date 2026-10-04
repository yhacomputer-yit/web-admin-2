import { usePage } from "@inertiajs/react";
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
        <StudentLayout active="assignments" title="Assignment" key={url} />
    );
}