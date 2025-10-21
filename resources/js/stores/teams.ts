import { defineStore } from "pinia";
import axios from "axios";

export const useTeamsStore = defineStore("teams", {
    state: () => {
        return {
            teamsMember: null,
        } as teams;
    },
    actions: {
        async fetchAllTeamsMembers() {
            try {
                const response = await axios.get("/api/teams");
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.teamsMember = response.data.data.teamsMembers;
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
    },
});

interface teams {
    teamsMember: teamsMember[] | null;
}

export interface teamsMember {
    type: string;
    name: string;
    url: string;
    bio: string;
    role: string;
}
