#include <stdio.h>

int verifica(unsigned char (*grid)[9][9], int r, int c, unsigned char n);
int soluzione(unsigned char (*grid)[9][9]);

void solve(unsigned char (*problem)[9][9]) {
    if (soluzione(problem)) {
        printf("Soluzione:\n");
    } else {
        printf("Ricontrolla numeri del sudoku.\n");
    }
}

int soluzione(unsigned char (*grid)[9][9]) {
    for (int r = 0; r < 9; ++r) {
        for (int c = 0; c < 9; ++c) {
            if ((*grid)[r][c] == 0) {
                for (unsigned char n = 1; n <= 9; ++n) {
                    if (verifica(grid, r, c, n) && ((*grid)[r][c] = n, soluzione(grid) ) ) {
                        return 1;
                    }
                    (*grid)[r][c] = 0;
                }
                return 0;
            }
        }
    }
    return 1;
}

int verifica(unsigned char (*grid)[9][9], int r, int c, unsigned char n) {
    int siniziale = 3 * (r / 3);
    int sfinale = 3 * (c / 3);

    for (int i = 0; i < 9; ++i) {
        int sini = siniziale + i / 3;
        int sfin = sfinale + i % 3;

        if ((*grid)[sini][sfin] == n ||
            (*grid)[r][i] == n ||
            (*grid)[i][c] == n) {
            return 0;
        }
    }
    return 1;
}